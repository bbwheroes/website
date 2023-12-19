import postgres from "postgres";

const sql = postgres({
  database: "core",
  user: "bbwheroes",
  password: process.env.DB_PASSWORD,
  host: process.env.DB_HOST,
  idle_timeout: 20,
});

export default sql;
