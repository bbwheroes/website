"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import ContributeInput from "../_components/Contribute/ContributeInput";
import CollaboratorsInput from "../_components/Contribute/CollaboratorsInput";
import { moduleValidator, taskNameValidator, teacherValidator } from "../_lib/validators";
import { slugifyTaskName } from "../_helpers/functions";

export default function Contribute() {
  const [module, setModule] = useState("");
  const [teacher, setTeacher] = useState("");
  const [taskName, setTaskName] = useState("");
  const [slugifiedTaskName, setSlugifiedTaskName] = useState("");
  const [collaborators, setCollaborators] = useState([]);
  const [isValid, setIsValid] = useState(false);

  useEffect(() => {
    setSlugifiedTaskName(slugifyTaskName(taskName));
  }, [taskName]);

  useEffect(() => {
    const moduleValidation = moduleValidator.safeParse(parseInt(module));
    const teacherValidation = teacherValidator.safeParse(teacher);
    const taskNameValidation = taskNameValidator.safeParse(taskName);

    if (
      !moduleValidation.success ||
      !teacherValidation.success ||
      !taskNameValidation.success
    ) {
      return setIsValid(false);
    }

    return setIsValid(true);
  }, [module, teacher, taskName, collaborators]);

  return (
    <main className="max-w-3xl px-12 py-16">
      <h1 className="text-center text-2xl text-white md:text-4xl">Create a proposal</h1>
      <p className="my-12 text-gray-400">
        We appreciate that you want contribute to this network. Please fill out this quick
        proposal and after a quick review you should get notifed by email. After an
        approval we run every necessary process to create your environment.{" "}
        <Link
          href="https://github.com/bbwheroes/system/tree/main/processes"
          target="_blank"
          className="text-bbw-400 underline duration-100 hover:text-bbw-500"
        >
          More informations here.
        </Link>
      </p>

      <form className="flex flex-col gap-6 text-white">
        <ContributeInput
          name="Module number"
          type="number"
          placeholder="431"
          charLimit={3}
          value={module}
          setValue={setModule}
          validationSchema={moduleValidator}
        />
        <ContributeInput
          name="Teacher (first 4 letters only)"
          type="text"
          placeholder="ober"
          charLimit={4}
          value={teacher}
          setValue={setTeacher}
          validationSchema={teacherValidator}
        />

        <ContributeInput
          name="Complete task name"
          type="text"
          placeholder="Linux Cookbook"
          value={taskName}
          setValue={setTaskName}
          validationSchema={taskNameValidator}
        />

        <ContributeInput
          name="Slugified task name (auto-generated)"
          type="text"
          placeholder="linux_cookbook"
          value={slugifiedTaskName}
          readonly={true}
        />

        <CollaboratorsInput
          collaborators={collaborators}
          setCollaborators={setCollaborators}
        />

        <button
          className={`m-auto w-min rounded-md  bg-bbw-400 px-4 py-2 font-medium text-gray-900 duration-100 ${
            isValid ? "hover:bg-bbw-500" : "cursor-not-allowed opacity-50"
          }`}
          type="submit"
          disabled={!isValid}
        >
          Submit
        </button>
      </form>
    </main>
  );
}
