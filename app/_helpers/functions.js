export function repoNameFromProject(project) {
  return `m${project.module}-${project.teacher}-${project.slugified_task_name}-${project.username}`;
}

export function slugifyTaskName(taskName) {
  let slug = taskName.toLowerCase();
  slug = slug.replace(/[\s\W]+/g, "_");

  return slug;
}
