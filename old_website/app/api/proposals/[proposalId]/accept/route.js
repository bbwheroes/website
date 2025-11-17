import { closeProjectProposal, getProject, getProposal } from "@/app/_helpers/db";
import { repoNameFromProject } from "@/app/_helpers/functions";
import { createRepository, inviteCollaborators } from "@/app/_helpers/github";
import { authOptions } from "@/app/_lib/auth";
import { getServerSession } from "next-auth";
import { NextResponse } from "next/server";

export async function GET(req, { params }) {
  const { proposalId } = params;

  const proposal = await getProposal(proposalId);
  const project = await getProject(proposal.project_id);

  await createRepository(repoNameFromProject(project));
  await inviteCollaborators(proposal.data.collaborators, project);

  await closeProjectProposal(project.id);

  return NextResponse.json({ id: params.proposalId });
}
