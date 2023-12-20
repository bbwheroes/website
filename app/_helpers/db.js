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

export default sql;
