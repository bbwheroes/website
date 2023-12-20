"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import ContributeInput from "../_components/Contribute/ContributeInput";
import { z } from "zod";
import CollaboratorsInput from "../_components/Contribute/CollaboratorsInput";

export default function Contribute() {
  const [module, setModule] = useState("");
  const [teacher, setTeacher] = useState("");
  const [taskName, setTaskName] = useState("");
  const [slugifiedTaskName, setSlugifiedTaskName] = useState("");
  const [collaborators, setCollaborators] = useState([]);

  const [errors, setErrors] = useState({});

  useEffect(() => {
    let slug = taskName.toLowerCase();
    slug = slug.replace(/[\s\W]+/g, "_");

    setSlugifiedTaskName(slug);
  }, [taskName]);

  const moduleSchema = z.number().positive().finite().gte(100).lte(999);
  const teacherSchema = z
    .string()
    .min(4)
    .max(4)
    .regex(/^[a-z][A-Z]$/, { message: "Must contain only letters" });
  const taskNameSchema = z
    .string()
    .min(6)
    .regex(/^[a-zA-Z][a-zA-Z ]*[a-zA-Z]$/, {
      message:
        "Must contain only letters and whitespaces and not start or end with a whitespace",
    });

  useEffect(() => {
    const moduleValidation = moduleSchema.safeParse(parseInt(module));
    if (!moduleValidation.success) setErrors({ module: moduleValidation.error.issues[0].message, ...errors});
    
    const teacherValidation = teacherSchema.safeParse(teacher)
    if (!teacherValidation.success) setErrors({ teacher: teacherValidation.error.issues[0].message, ...errors});
    
    const taskNameValidation = taskNameSchema.safeParse(taskName)
    if (!taskNameValidation.success) setErrors({ taskName: taskNameValidation.error.issues[0].message, ...errors});
  }, [module, teacher, taskName, collaborators])

  return (
    <main className="max-w-3xl px-12 py-16">
      <h1 className="text-center text-2xl text-white md:text-4xl">
        Create a proposal
      </h1>
      <p className="text-gray-400 my-12">
        We appreciate that you want contribute to this network. Please fill out this quick
        proposal and after a quick review you should get notifed by email. After an
        approval we run every necessary process to create your environment.{" "}
        <Link
          href="https://github.com/bbwheroes/system/tree/main/processes"
          target="_blank"
          className="text-bbw-400 hover:text-bbw-500 duration-100 underline"
        >
          More informations here.
        </Link>
      </p>

      <form className="flex flex-col gap-6 text-white">
        <ContributeInput
          name="Module number"
          errorName="module"
          type="number"
          placeholder="431"
          charLimit={3}
          value={module}
          setValue={setModule}
          errors={errors}
        />
        <ContributeInput
          name="Teacher (first 4 letters only)"
          errorName="teacher"
          type="text"
          placeholder="ober"
          charLimit={4}
          value={teacher}
          setValue={setTeacher}
          errors={errors}
        />

        <ContributeInput
          name="Complete task name"
          errorName="taskName"
          type="text"
          placeholder="Linux Cookbook"
          value={taskName}
          setValue={setTaskName}
          errors={errors}
        />

        <ContributeInput
          name="Slugified task name (auto-generated)"
          type="text"
          placeholder="linux_cookbook"
          value={slugifiedTaskName}
          readonly={true}
        />

        <CollaboratorsInput
          errorName="collaborators"
          collaborators={collaborators}
          setCollaborators={setCollaborators}
        />

        <button className="bg-bbw-400 w-min px-4 py-2 rounded-md text-gray-900 font-medium hover:bg-bbw-500 duration-100 m-auto" type="submit">Submit</button>
      </form>
    </main>
  );
}
