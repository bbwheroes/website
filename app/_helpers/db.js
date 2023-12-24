import postgres from "postgres";
import { PROPOSALS } from "../_lib/proposals";
import sendWebhook from "./discord";

const sql = postgres({
  database: "core",
  user: "bbwheroes",
  password: process.env.DB_PASSWORD,
  host: process.env.DB_HOST,
  idle_timeout: 20,
});

export async function getProject(id) {
  const projectRes = await sql`SELECT id,
                      module,
                      teacher,
                      task_name,
                      slugified_task_name,
                      username
                        FROM projects
                        WHERE id = ${id}`;
  return projectRes[0];
}

export async function getProposal(id) {
  const proposalRes = await sql`SELECT *
                                  FROM proposals
                                  WHERE id = ${id}`;

  return proposalRes[0];
}

export async function getProjects() {
  return await sql`SELECT id,
                      module,
                      teacher,
                      task_name,
                      slugified_task_name,
                      username
                    FROM projects
                    WHERE open_proposal = false`;
}

export async function getProjectsCount() {
  const projectsCountRes = await sql`SELECT COUNT(*)
                                      FROM projects
                                      WHERE open_proposal = false`;

  return projectsCountRes[0].count;
}

export async function upsertUser(githubUserId, bbwEmail = null, discordId = null) {
  const insertUserRes = await sql`INSERT INTO users (github_id, bbw_email, discord_id)
                    VALUES (${githubUserId}, ${bbwEmail}, ${discordId})
                    ON CONFLICT (github_id)
                    DO UPDATE SET
                      bbw_email = ${bbwEmail},
                      discord_id = ${discordId}
                    RETURNING *`;

  return insertUserRes[0];
}

export async function insertProjectPropoal({
  githubUser,
  module,
  teacher,
  taskName,
  slugifiedTaskName,
  username,
  collaborators,
}) {
  const githubUserId =
    typeof githubUser.id === "string" ? parseInt(githubUser.id) : githubUser.id;
  module =
    typeof module === "number"
      ? JSON.stringify(module).toLowerCase()
      : module.toLowerCase();
  teacher = teacher.toLowerCase();
  username = username.toLowerCase();

  const ownerRes = await sql`SELECT * FROM users WHERE github_id = ${githubUserId}`;

  const insertProjectRes =
    await sql`INSERT INTO projects (owner_id, module, teacher, task_name, slugified_task_name, username, open_proposal)
                    VALUES (${ownerRes[0].id}, ${module}, ${teacher}, ${taskName}, ${slugifiedTaskName}, ${username}, true)
                    RETURNING *`;
  const project = insertProjectRes[0];

  const proposalRes = await sql`INSERT INTO proposals (project_id, type, data) VALUES (${
    project.id
  }, ${PROPOSALS.PROJECT}, ${{ collaborators }}) RETURNING *`;
  let proposal = proposalRes[0];

  await sendWebhook({ project, proposal, githubUser });

  delete proposal.id;

  return { project, proposal };
}

export async function rejectProposal(proposalId) {
  await sql`DELETE FROM projects
              USING proposals
              WHERE projects.id = proposals.project_id
                AND proposals.id = ${proposalId}`;
  await sql`DELETE FROM proposals
              WHERE id = ${proposalId}`;
  return true;
}

export async function closeProjectProposal(projectId) {
  await sql`UPDATE projects
              SET open_proposal = false
              WHERE id = ${projectId}`;
  await sql`DELETE FROM proposals
              WHERE project_id = ${projectId}`;
  return true;
}

export default sql;
