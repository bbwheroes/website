import Link from "next/link";
import { twMerge } from "tailwind-merge";

export default function NavLink({ href, target, className, children }) {
  return (
    <Link
      href={href}
      target={target}
      className={twMerge(
        "flex items-center gap-2 text-white duration-100 hover:text-gray-400",
        className
      )}
    >
      {children}
    </Link>
  );
}
