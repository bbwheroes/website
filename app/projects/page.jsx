import Projects from "../_components/Projects/Projects";
import sql from "../_helpers/db";

export default async function ProjectsOverview() {
  const projects = await sql`SELECT id,
                                    module,
                                    teacher,
                                    task_name,
                                    slugified_task_name,
                                    username
                              FROM projects
                              WHERE open_proposal = false`;
  const projectsCountRes = await sql`SELECT COUNT(*)
                                      FROM projects
                                      WHERE open_proposal = false`;
  const projectsCount = projectsCountRes[0].count;

  return (
    <section className="px-12 py-16">
      <h2 className="mb-12 text-center text-2xl text-white md:text-4xl">
        Search from <strong>{projectsCount}</strong> projects
      </h2>
      <Projects projectsData={projects} limit={20} />
    </section>
  );
}
