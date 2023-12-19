const withNextra = require("nextra")({
  theme: "nextra-theme-docs",
  themeConfig: "./theme.config.jsx",
  flexsearch: true,
  staticImage: true,
  defaultShowCopyCode: true,
});

module.exports = withNextra({ images: {
  remotePatterns: [
    {
      protocol: 'https',
      hostname: 'avatars.githubusercontent.com',
    },
  ],
}, });
