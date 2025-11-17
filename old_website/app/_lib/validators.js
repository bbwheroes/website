const { z } = require("zod");

export const moduleValidator = z.number().positive().finite().gte(100).lte(999);

export const teacherValidator = z
  .string()
  .min(4)
  .max(4)
  .regex(/^[a-zA-Z]{4}$/, { message: "Must contain only letters" });

export const taskNameValidator = z
  .string()
  .min(6)
  .regex(/^[a-zA-Z][a-zA-Z ]*[a-zA-Z]$/, {
    message:
      "Must contain only letters and whitespaces and not start or end with a whitespace",
  });

export const collaboratorsValidator = z
  .array(z.string())
  .refine((items) => new Set(items).size === items.length, {
    message: "Must be an array of unique strings",
  });
