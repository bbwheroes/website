/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./components/**/*.{js,ts,jsx,tsx,mdx}",
    "./app/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        bbw: {
          50: "#f2f8c5",
          100: "#e6f18b",
          200: "#d9eb52",
          300: "#cde418",
          400: "#b3d800",
          500: "#8fb600",
          600: "#6b8c00",
          700: "#476200",
          800: "#233900",
          900: "#001000",
        },
      },
    },
  },
  plugins: [],
};
