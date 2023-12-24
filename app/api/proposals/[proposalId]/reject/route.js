import { rejectProposal } from "@/app/_helpers/db";
import { authOptions } from "@/app/_lib/auth";
import { getServerSession } from "next-auth";
import { NextResponse } from "next/server";

export async function GET(req, { params }) {
  const { proposalId } = params;

  await rejectProposal(proposalId);

  return NextResponse.json({ message: "Successfully deleted proposal" });
}
