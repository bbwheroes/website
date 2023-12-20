import { authOptions } from "@/app/_lib/auth";
import { getServerSession } from "next-auth";
import Image from "next/image";
import { FaChevronDown } from "react-icons/fa";

export default async function Profile({ user }) {
  return (
    <div className="flex items-center gap-2">
      <Image
        src={user.image}
        alt="Profile Picture"
        width={36}
        height={36}
        className="rounded-full"
      />
      <FaChevronDown className="text-xs text-white hover:text-gray-400" />
    </div>
  );
}
