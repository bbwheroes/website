import { Octokit } from "octokit";
import { repoNameFromProject } from "./functions";

const octokit = new Octokit({
  auth: process.env.GITHUB_API_PAT,
});

export async function getGithubUser(id) {
  const githubUser = await octokit.request("GET /user/{userId}", {
    userId: id,
    headers: {
      "X-GitHub-Api-Version": "2022-11-28",
    },
  });

  if (githubUser.message === "Not Found") {
    return null;
  }

  return githubUser.data;
}

export async function createRepository(repoName) {
  return await octokit.request("POST /orgs/{org}/repos", {
    org: "bbwheroes",
    name: repoName,
    has_projects: false,
    headers: {
      "X-GitHub-Api-Version": "2022-11-28",
    },
  });
}

export async function inviteCollaborators(collaborators, project) {
  const repoName = repoNameFromProject(project);

  [...collaborators, project.username].forEach(async (collaborator) => {
    await octokit.request("PUT /repos/{owner}/{repo}/collaborators/{username}", {
      owner: "bbwheroes",
      repo: repoName,
      username: collaborator,
      permission: "maintain",
      headers: {
        "X-GitHub-Api-Version": "2022-11-28",
      },
    });
  });
}

export default octokit;
