import Link from "next/link";
import { FaExternalLinkAlt } from "react-icons/fa";

export default function ServiceCard({ icon, title, description, link, comingSoon }) {
  return (
    <div
      className={`${
        comingSoon && "opacity-50"
      } flex h-full w-full items-center justify-between gap-4 rounded-md bg-gray-800 p-5 text-white`}
    >
      <div className="flex items-center gap-4">
        <div className="flex aspect-square h-16 items-center justify-center rounded-full bg-gray-700">
          {icon}
        </div>
        <div>
          <p className="text-xl leading-6">{title}</p>
          <span className="italic text-gray-400">{description}</span>
        </div>
      </div>
      {link && (
        <div>
          <Link href={link}>
            <div className="flex aspect-square h-10 items-center justify-center rounded-full bg-gray-700 text-gray-300 duration-100 hover:bg-gray-600">
              <FaExternalLinkAlt className="text-xs" />
            </div>
          </Link>
        </div>
      )}
      {comingSoon && <div className="italic">WIP</div>}
    </div>
  );
}
