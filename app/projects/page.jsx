import Projects from "../_components/Projects/Projects";
import { getProjects, getProjectsCount } from "../_helpers/db";

export default async function ProjectsOverview() {
  const projects = await getProjects();
  const projectsCount = await getProjectsCount();

  return (
    <section className="px-12 py-16">
      <h2 className="mb-12 text-center text-2xl text-white md:text-4xl">
        Search from <strong>{projectsCount}</strong> projects
      </h2>
      <Projects projectsData={projects} limit={20} />
    </section>
  );
}
