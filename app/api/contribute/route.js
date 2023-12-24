import { insertProjectPropoal } from "@/app/_helpers/db";
import { slugifyTaskName } from "@/app/_helpers/functions";
import { getGithubUser } from "@/app/_helpers/github";
import { authOptions } from "@/app/_lib/auth";
import {
  collaboratorsValidator,
  moduleValidator,
  taskNameValidator,
  teacherValidator,
} from "@/app/_lib/validators";
import { getServerSession } from "next-auth";
import { getToken } from "next-auth/jwt";
import { NextResponse } from "next/server";

export async function POST(req) {
  const session = await getServerSession(authOptions);

  if (!session) {
    return NextResponse.json({ error: "Not authorized" }, { status: 403 });
  }

  console.log("------------------------ 1");

  const token = await getToken({ req });
  const githubUserId = token.sub;

  const githubUser = await getGithubUser(githubUserId);

  if (!githubUser) {
    return NextResponse.json({ error: "Username not found on github" }, { status: 400 });
  }

  // Get the body
  let { module, teacher, taskName, collaborators } = await req.json();

  if (!module || !teacher || !taskName || !collaborators) {
    return NextResponse.json({ error: "Missing required fields" }, { status: 400 });
  }

  console.log("------------------------ 2");

  const moduleValidation = moduleValidator.safeParse(parseInt(module));
  const teacherValidation = teacherValidator.safeParse(teacher);
  const taskNameValidation = taskNameValidator.safeParse(taskName);
  const collaboratorsValidation = collaboratorsValidator.safeParse(collaborators);
  if (
    !moduleValidation.success ||
    !teacherValidation.success ||
    !taskNameValidation.success ||
    !collaboratorsValidation.success
  ) {
    return NextResponse.json(
      {
        error: [
          ...(!moduleValidation.success ? moduleValidation.error.issues : []),
          ...(!teacherValidation.success ? teacherValidation.error.issues : []),
          ...(!taskNameValidation.success ? taskNameValidation.error.issues : []),
          ...(!collaboratorsValidation.success
            ? collaboratorsValidation.error.issues
            : []),
        ],
      },
      { status: 400 }
    );
  }

  console.log("------------------------ 3");

  console.log(githubUser, collaborators);

  const indexOfOwnerInCollaborators = collaborators.findIndex(
    (collab) => collab.toLowerCase() === githubUser.login.toLowerCase()
  );

  if (indexOfOwnerInCollaborators !== -1) {
    collaborators.splice(indexOfOwnerInCollaborators, 1);
  }

  console.log("------------------------ 4");

  const slugifiedTaskName = slugifyTaskName(taskName);

  console.log("------------------------ 5");

  const { project, proposal } = await insertProjectPropoal({
    githubUser,
    module,
    teacher,
    taskName,
    slugifiedTaskName,
    username: githubUser.login,
    collaborators,
  });

  return NextResponse.json({ project, proposal });
}
