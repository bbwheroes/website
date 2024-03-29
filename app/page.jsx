import Navigation from "@/app/_components/Navigation/Navigation";
import Link from "next/link";
import ContributeLink from "./_components/Navigation/ContributeLink";
import NavLink from "./_components/Navigation/NavLink";
import { FaBook, FaCode, FaFile, FaGlobe, FaLink, FaServer } from "react-icons/fa";
import { FaArrowTrendUp } from "react-icons/fa6";
import ServiceCard from "./_components/Services/ServiceCard";
import Projects from "./_components/Projects/Projects";
import { getProjects, getProjectsCount } from "./_helpers/db";
import { getServerSession } from "next-auth";
import { authOptions } from "./_lib/auth";

export default async function Home() {
  const projects = await getProjects();
  const projectsCount = await getProjectsCount();

  return (
    <main>
      <section className="px-12 py-32">
        <h1 className="mb-12 text-center text-5xl font-medium text-white md:text-7xl">
          Where BBW students come together to form a community.
        </h1>
        <div className="flex justify-center gap-4">
          <NavLink
            href="/projects"
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
        <div>
          <p className="text-center italic text-white">
            Coming later.{" "}
            <Link
              href="https://discord.gg/xbUfU4FYSc"
              target="_blank"
              className="text-bbw-400 duration-100 hover:text-bbw-500"
            >
              Join our discord
            </Link>{" "}
            to stay up to date.
          </p>
          {/* <ServiceCard
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
          /> */}
        </div>
      </section>
      <hr className="border-gray-800" />
      <section className="px-12 py-16">
        <h2 className="mb-12 text-center text-2xl text-white md:text-4xl">
          Search from <strong>{projectsCount}</strong> projects
        </h2>
        <Projects projectsData={projects} limit={10} />
      </section>
    </main>
  );
}
