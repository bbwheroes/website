import { NextAuthOptions } from "next-auth";
import GithubProvider from "next-auth/providers/github";
import { upsertUser } from "../_helpers/db";

export const authOptions = {
  // Secret for Next-auth, without this JWT encryption/decryption won't work
  secret: process.env.NEXTAUTH_SECRET,
  providers: [
    GithubProvider({
      clientId: process.env.GITHUB_APP_CLIENT_ID,
      clientSecret: process.env.GITHUB_APP_CLIENT_SECRET,
    }),
  ],
  callbacks: {
    async signIn({ profile }) {
      if (/@lernende.bbw.ch$/.test(profile.email)) {
        await upsertUser(profile.id, profile.email)
      } else {
        await upsertUser(profile.id)
      }
      return true;
    }
  },
};
