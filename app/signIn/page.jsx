"use client";

import { SessionProvider, signIn, useSession } from "next-auth/react";
import { useEffect } from "react";
import { useRouter, useSearchParams } from "next/navigation";

function SignInComponent() {
  const router = useRouter();
  const { status } = useSession();
  const searchParams = useSearchParams();

  useEffect(() => {
    if (status === "unauthenticated") {
      void signIn("github");
    } else if (status === "authenticated") {
      const callbackUrl = window.location.search.includes("callbackUrl=")
        ? decodeURIComponent(window.location.search.replace(/^\?callbackUrl=/, ""))
        : "/";

      void router.push(callbackUrl);
    }
  }, [status, router]);

  return null;
}

export default function SignIn() {
  return (
    <SessionProvider>
      <SignInComponent />
    </SessionProvider>
  );
}
