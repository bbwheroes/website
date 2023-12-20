"use client";

import { useState } from "react";

export default function ContributeInput({
  errorName,
  name,
  type,
  placeholder,
  charLimit,
  value,
  setValue,
  readonly,
  errors
}) {
  const handleTeacherChange = (e) => {
    if (readonly || (charLimit && e.target.value.length > charLimit)) return;

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
      {errors && errors[errorName] && <span className="text-red-400">{errors[errorName]}</span>}
    </div>
  );
}
