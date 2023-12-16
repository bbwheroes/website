import Image from "next/image";
import Link from "next/link";
import { FaExternalLinkAlt } from "react-icons/fa";
import ContributeLink from "./ContributeLink";
import NavLink from "./NavLink";

export default function Navigation() {
  return (
    <nav className="m-auto flex w-full max-w-7xl items-center justify-between px-12 py-4 font-medium">
      <Link href="/">
        <Image src="bbwheroes.svg" alt="BBW Heroes Logo" width={100} height={100} />
      </Link>
      <div className="flex items-center gap-8">
        <NavLink href="/">Projects</NavLink>
        <NavLink href="/wiki" target="_blank">
          Wiki <FaExternalLinkAlt className="text-xs" />
        </NavLink>
        <NavLink href="https://discord.gg/xbUfU4FYSc" target="_blank">
          Discord
          <FaExternalLinkAlt className="text-xs" />
        </NavLink>
        <ContributeLink />
      </div>
    </nav>
  );
}
