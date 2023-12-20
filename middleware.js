import { withAuth } from "next-auth/middleware";

export default withAuth({
  callbacks: {
    async signIn({ user, account, profile, email, credentials }) {
      console.log(Hello, { user, account, profile, email, credentials });
      return true;
    },
  },
  pages: {
    signIn: "/signIn",
  },
});

export const config = { matcher: ["/contribute"] };
