import Image from "next/image";
import Navigation from "./app/_components/Navigation/Navigation";
import { Navbar } from "nextra-theme-docs";

export default {
  // logo: <Image src="bbwheroes.svg" alt="BBW Heroes Logo" width={100} height={100} />,
  logo: <span>BBW Heroes Wiki</span>,
  logoLink: "/wiki",
  project: {
    link: "https://github.com/bbwheroes/website/",
  },
  search: {
    placeholder: "Search wiki...",
  },
};
