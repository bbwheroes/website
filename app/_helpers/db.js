import postgres from "postgres";

const sql = postgres({
  database: "core",
  user: "bbwheroes",
  password: process.env.DB_PASSWORD,
  host: process.env.DB_HOST,
  idle_timeout: 20,
});

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
  return await sql`INSERT INTO users (github_id, bbw_email, discord_id)
                    VALUES (${githubUserId}, ${bbwEmail}, ${discordId})
                    ON CONFLICT (github_id)
                    DO UPDATE SET
                      bbw_email = ${bbwEmail},
                      discord_id = ${discordId}
                    RETURNING *`;
}

export default sql;
