import Navigation from "@/app/_components/Navigation/Navigation";
import Link from "next/link";
import ContributeLink from "./_components/Navigation/ContributeLink";
import NavLink from "./_components/Navigation/NavLink";
import { FaBook, FaCode, FaFile, FaGlobe, FaLink, FaServer } from "react-icons/fa";
import { FaArrowTrendUp } from "react-icons/fa6";
import ServiceCard from "./_components/Services/ServiceCard";

export default function Home() {
  return (
    <main>
      <hr className="border-gray-800" />
      <section className="px-12 py-32">
        <h1 className="mb-12 text-center text-5xl font-medium text-white md:text-7xl">
          Where BBW students come together and create a community.
        </h1>
        <div className="flex justify-center gap-4">
          <NavLink
            href="/"
            className="rounded-lg bg-gray-500 px-3 py-1.5 duration-100 hover:bg-gray-600 hover:text-white"
          >
            <FaBook className="text-xs" />
            Projects
          </NavLink>
          <ContributeLink />
        </div>
      </section>
      <hr className="border-gray-800" />
      <section className="px-12 py-16">
        <h2 className="mb-12 text-center text-2xl text-white md:text-4xl">
          Services we provide
        </h2>
        <div className="grid w-full items-center gap-4 md:grid-cols-2">
          <ServiceCard
            icon={<FaLink className="text-2xl text-blue-400" />}
            title="Link Shortener"
            description="Shorten your links with ease."
            link="/"
          />
          <ServiceCard
            icon={<FaFile className="text-2xl text-green-400" />}
            title="Temp File Share"
            description="Simply upload and share your files."
            link="/"
          />
          <ServiceCard
            icon={<FaCode className="text-2xl text-orange-400" />}
            title="Share Code"
            description="Quickly share your code snippets."
            link="/"
          />
          <ServiceCard
            icon={<FaServer className="text-2xl text-red-400" />}
            title="App Hosting"
            description="Special apps can get hosting via Docker."
          />
          <ServiceCard
            icon={<FaGlobe className="text-2xl text-gray-400" />}
            title="Custom subdomain"
            description="You can ask for a subdomain."
          />
          <ServiceCard
            icon={<FaArrowTrendUp className="text-2xl text-purple-400" />}
            title="Web Analytics"
            description="Track your website's traffic easily."
            comingSoon={true}
          />
        </div>
      </section>
      <hr className="border-gray-800" />
      <section className="px-12 py-16">
        <h2 className="mb-12 text-center text-2xl text-white md:text-4xl">
          Search from <strong>27</strong> projects
        </h2>
        <div className="grid w-full items-center gap-4 md:grid-cols-2"></div>
      </section>
    </main>
  );
}
