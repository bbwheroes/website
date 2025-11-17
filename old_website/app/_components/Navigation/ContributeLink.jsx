import Link from "next/link";
import { FaPlus } from "react-icons/fa";

export default function ContributeLink() {
  return (
    <Link
      href="/contribute"
      className="flex items-center gap-2 rounded-lg bg-bbw-400 px-3 py-1.5 text-gray-900 duration-100 hover:bg-bbw-500"
    >
      <FaPlus className="text-xs" />
      Contribute
    </Link>
  );
}
