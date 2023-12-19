import postgres from "postgres";

const sql = postgres({
  database: "core",
  user: "bbwheroes",
  password: "bbwheroes",
  idle_timeout: 20,
});

export default sql;
