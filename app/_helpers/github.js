export async function getGithubUser(id) {
  const githubRes = await fetch(`https://api.github.com/user/${id}`);
  const githubUser = await githubRes.json();

  if(githubUser.message === "Not Found"){
    return null;
  }

  return githubUser;
}