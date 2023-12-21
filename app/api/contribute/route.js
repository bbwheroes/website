import { slugifyTaskName } from "@/app/_helpers/functions";
import { authOptions } from "@/app/_lib/auth";
import { moduleValidator, taskNameValidator, teacherValidator } from "@/app/_lib/validators";
import { getServerSession } from "next-auth";
import { NextResponse } from "next/server";

export async function POST(req) {
  const session = await getServerSession(authOptions);

  // if(!session){
  //   return NextResponse.json({error: "Not authorized"}, {status: 403})
  // }

  // Get the body
  let { module, teacher, taskName } = await req.json();

  if (!module || !teacher || !taskName) {
    return NextResponse.json({ error: "Missing required fields" }, { status: 400 });
  }

  const moduleValidation = moduleValidator.safeParse(module);
  const teacherValidation = teacherValidator.safeParse(teacher);
  const taskNameValidation = taskNameValidator.safeParse(taskName);
  if (
    !moduleValidation.success ||
    !teacherValidation.success ||
    !taskNameValidation.success
  ) {
    return NextResponse.json({ error: [
      ...(!moduleValidation.success ? moduleValidation.error.issues : []),
      ...(!teacherValidation.success ? teacherValidation.error.issues : []),
      ...(!taskNameValidation.success ? taskNameValidation.error.issues : [])
    ] }, { status: 400 });
  }

  module = JSON.stringify(module).toLowerCase();
  teacher = teacher.toLowerCase();
  taskName = taskName.toLowerCase();
  const slugifiedTaskName = slugifyTaskName(taskName);

  return NextResponse.json({module, teacher, taskName, slugifiedTaskName});
}
