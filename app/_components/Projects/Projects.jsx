"use client";

import ProjectRow from "./ProjectRow";
import ProjectSearch from "./ProjectSearch";
import { useEffect, useState } from "react";

export default function Projects({ projectsData, limit }) {
  const [projects, setProjects] = useState(projectsData);
  const [searchedProjects, setSearchedProjects] = useState(projectsData.slice(0, limit));
  const [searchTimeout, setSearchTimeout] = useState(null);

  const [search, setSearch] = useState({
    module: "",
    teacher: "",
    name: "",
    username: "",
  });

  useEffect(() => {
    // Put first 10 projects in state
    setSearchedProjects(projectsData.slice(0, 10));
  }, []);

  useEffect(() => {
    clearTimeout(searchTimeout);

    // Create timeout for 250ms so you don't instantly go through all projects and safe resources
    setSearchTimeout(
      setTimeout(() => {
        searchInProjects();
      }, 250)
    );
  }, [search]);

  function searchInProjects() {
    // If search bar is empty, show as many projects as limit defined
    if (!search.module && !search.teacher && !search.name && !search.username)
      return setSearchedProjects(projectsData.slice(0, limit));

    const lowercaseSearch = Object.fromEntries(
      Object.entries(search).map(([key, value]) => [key, value.toLowerCase()])
    );

    setSearchedProjects(
      projects
        .filter((project) => {
          project = Object.fromEntries(
            Object.entries(project).map(([key, value]) => [
              key,
              typeof value === "string" ? value.toLowerCase() : value,
            ])
          );

          return (
            JSON.stringify(project.module).includes(lowercaseSearch.module) &&
            project.teacher.includes(lowercaseSearch.teacher) &&
            (project.task_name.includes(lowercaseSearch.name) ||
              project.slugified_task_name.includes(lowercaseSearch.name)) &&
            project.username.includes(lowercaseSearch.username)
          );
        })
        .slice(0, limit)
    );
  }

  return (
    <>
      <ProjectSearch search={search} setSearch={setSearch} />
      <p className="text-gray-500 italic mb-1">Showing <span className="font-bold">{searchedProjects.length}</span> results</p>
      <ul className="flex flex-col gap-4">
        {searchedProjects.map((project) => (
          <ProjectRow project={project} key={project.id} />
        ))}
      </ul>
    </>
  );
}
