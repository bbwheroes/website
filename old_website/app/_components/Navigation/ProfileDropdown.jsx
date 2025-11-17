"use client";

import { signOut } from "next-auth/react";
import Link from "next/link";
import { useEffect, useRef, useState } from "react";
import { FaChevronDown } from "react-icons/fa";

export default function ProfileDropdown() {
  const [isOpen, setIsOpen] = useState(false);
  const dropdownRef = useRef(null);

  // Detect clicks outside of dropdown
  useEffect(() => {
    const handleOutSideClick = (event) => {
      if (!dropdownRef.current?.contains(event.target) && isOpen) {
        setIsOpen(false);
      }
    };
    window.addEventListener("mousedown", handleOutSideClick);

    return () => {
      window.removeEventListener("mousedown", handleOutSideClick);
    };
  }, [dropdownRef, isOpen]);

  return (
    <div className="relative" ref={dropdownRef}>
      <button
        className="duratio-100 p-3 text-white hover:text-gray-400"
        onClick={() => setIsOpen(!isOpen)}
      >
        <FaChevronDown className="text-xs" />
      </button>
      {isOpen && (
        <ul className="absolute right-0 top-10 w-40 list-none rounded-md">
          {/* <li className="">
            <Link
              href="/"
              onClick={() => setIsOpen(false)}
              className="block w-full rounded-t-md bg-gray-800 px-3 py-1.5 text-white duration-100 hover:bg-gray-700"
            >
              Profile
            </Link>
          </li>
          <hr className="w-full border-gray-700" /> */}
          <li>
            <button
              onClick={() => {
                setIsOpen(false);
                signOut({ callbackUrl: "/" });
              }}
              className="block w-full rounded-md bg-gray-800 px-3 py-1.5 text-left text-white duration-100 hover:bg-gray-700"
            >
              Logout
            </button>
          </li>
        </ul>
      )}
    </div>
  );
}
