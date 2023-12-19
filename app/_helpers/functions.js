export function repoNameFromProject(project) {
  return `m${project.module}-${project.teacher}-${project.slugified_task_name}-${project.username}`;
}
