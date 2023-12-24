function buildWebhookData(project, proposal, githubUser) {
  return {
    username: "Proposals",
    embeds: [
      {
        title: "New Proposal!",
        color: 5814783,
        ...(githubUser.avatar_url
          ? {
              thumbnail: {
                url: `${githubUser.avatar_url}`,
              },
            }
          : {}),
        fields: [
          {
            name: "Module",
            value: project.module,
            inline: true,
          },
          {
            name: "Teacher",
            value: project.teacher,
            inline: true,
          },
          {
            name: "Task name",
            value: project.task_name,
            inline: false,
          },
          {
            name: "Username",
            value: `${project.username} ([Github profile](${githubUser.html_url}))`,
            inline: false,
          },
          {
            name: "Collaborators",
            value:
              proposal.data.collaborators.length === 0
                ? "-"
                : proposal.data.collaborators.join(", "),
          },
          {
            name: "Accept",
            value: `[Accept](${process.env.NEXT_PUBLIC_APP_URL}/api/proposals/${proposal.id}/accept)`,
            inline: true,
          },
          {
            name: "Reject",
            value: `[Reject](${process.env.NEXT_PUBLIC_APP_URL}/api/proposals/${proposal.id}/reject)`,
            inline: true,
          },
        ],
      },
    ],
  };
}

export default async function sendWebhook({ project, proposal, githubUser }) {
  console.log(githubUser);
  const webhookData = buildWebhookData(project, proposal, githubUser);

  const webhookRes = await fetch(process.env.DISCORD_WEBHOOK_URL, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(webhookData),
  });
}
