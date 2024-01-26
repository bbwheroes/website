import { repoNameFromProject } from "@/app/_helpers/functions";
import { FaGithub } from "react-icons/fa";

export default function ProjectRow({ project }) {
  const fullProjectName = repoNameFromProject(project);

  return (
    <li className="flex w-full items-center justify-between rounded-md bg-gray-800 p-4 text-white shadow-sm shadow-gray-700">
      <div>
        <p className="text-lg font-medium">{fullProjectName}</p>
        <span className="text-gray-400">{project.task_name}</span>
      </div>
      <a href={`https://github.com/bbwheroes/${fullProjectName}`} target="_blank" className="flex aspect-square h-10 items-center justify-center rounded-full bg-gray-700 text-gray-300 duration-100 hover:bg-gray-600">
        <FaGithub className="text-lg" />
      </a>
    </li>
  );
}
