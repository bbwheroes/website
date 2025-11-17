import { authOptions } from "@/app/_lib/auth";
import { getServerSession } from "next-auth";
import Image from "next/image";
import ProfileDropdown from "./ProfileDropdown";

export default async function Profile({ user }) {
  return (
    <div className="flex items-center rounded-full bg-gray-800">
      <Image
        src={user.image}
        alt="Profile Picture"
        width={36}
        height={36}
        className="rounded-full"
      />
      <ProfileDropdown />
    </div>
  );
}
