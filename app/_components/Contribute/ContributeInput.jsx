"use client";

import { useEffect, useState } from "react";

export default function ContributeInput({
  name,
  type,
  placeholder,
  charLimit,
  value,
  setValue,
  readonly,
  validationSchema,
}) {
  const [error, setError] = useState(null);

  // Run validation once on render
  useEffect(() => {
    if (!readonly) {
      const validatedData = validationSchema.safeParse(type === "number" ? null : "");

      if (!validatedData.success) {
        setError(validatedData.error.issues[0].message);
      }
    }
  }, [validationSchema]);

  const handleTeacherChange = (e) => {
    setError(null);

    if (readonly || (charLimit && e.target.value.length > charLimit)) return;

    const validatedData = validationSchema.safeParse(
      type === "number" ? parseInt(e.target.value) : e.target.value
    );

    if (!validatedData.success) {
      setError(validatedData.error.issues[0].message);
    }

    setValue(e.target.value);
  };

  return (
    <div className={`flex ${charLimit ? "items-center gap-4" : "flex-col"} gap-2`}>
      <label htmlFor={name} className="text-lg">
        {name}
        <span className="text-red-400">*</span>
      </label>
      <input
        type={type}
        name={name}
        placeholder={placeholder}
        value={value}
        onChange={handleTeacherChange}
        className={`rounded-md border border-gray-600 bg-gray-700 px-3 py-1 font-mono ${
          charLimit && `box-content w-[${charLimit}ch]`
        } ${readonly && "opacity-50"}`}
        disabled={readonly}
        required
      />
      {error && <span className="text-red-400">{error}</span>}
    </div>
  );
}
