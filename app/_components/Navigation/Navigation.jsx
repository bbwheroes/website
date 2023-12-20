import Image from "next/image";
import Link from "next/link";
import { FaExternalLinkAlt } from "react-icons/fa";
import ContributeLink from "./ContributeLink";
import NavLink from "./NavLink";
import Profile from "./Profile";
import { getServerSession } from "next-auth";
import { authOptions } from "@/app/_lib/auth";

export default async function Navigation() {
  const authSession = await getServerSession(authOptions);
  const user = authSession && authSession.user;

  return (
    <nav className="m-auto flex w-full max-w-7xl items-center justify-between px-12 py-4 font-medium">
      <Link href="/">
        <Image src="bbwheroes.svg" alt="BBW Heroes Logo" width={100} height={100} />
      </Link>
      <ul className="flex items-center gap-8">
        <NavLink href="/projects">Projects</NavLink>
        <NavLink href="/wiki" target="_blank">
          Wiki <FaExternalLinkAlt className="text-xs" />
        </NavLink>
        <NavLink href="https://discord.gg/xbUfU4FYSc" target="_blank">
          Discord
          <FaExternalLinkAlt className="text-xs" />
        </NavLink>
        <ContributeLink />
        {user && <Profile user={user} />}
      </ul>
    </nav>
  );
}
