export default function ProjectSearch({ search, setSearch }) {
  const handleModuleChange = (e) => {
    if (e.target.value.length > 3) return;

    setSearch({ ...search, module: e.target.value });
  };

  const handleTeacherChange = (e) => {
    if (e.target.value.length > 4) return;

    setSearch({ ...search, teacher: e.target.value });
  };

  const handleNameChange = (e) => {
    setSearch({ ...search, name: e.target.value });
  };

  const handleUsernameChange = (e) => {
    // GitHub username limit is 39 characters
    if (e.target.value.length > 39) return;

    setSearch({ ...search, username: e.target.value });
  };

  return (
    <search className="m-auto my-10 max-w-max font-mono text-white outline-none">
      <input
        type="number"
        name="module"
        id="module"
        placeholder="431"
        className="box-content w-[3ch] rounded-l-lg border-2 border-gray-600 bg-gray-700 px-3 py-1"
        value={search.module}
        onChange={handleModuleChange}
      />
      <span className="m-1">-</span>
      <input
        type="text"
        name="teacher"
        id="teacher"
        placeholder="ober"
        className="box-content w-[4ch]  border-2 border-gray-600 bg-gray-700 px-3 py-1"
        value={search.teacher}
        onChange={handleTeacherChange}
      />
      <span className="m-1">-</span>
      <input
        type="text"
        name="name"
        id="name"
        className="box-content w-[32ch] border-2 border-gray-600 bg-gray-700 px-3 py-1"
        placeholder="Linux Cookbook"
        value={search.name}
        onChange={handleNameChange}
      />
      <span className="m-1">-</span>
      <input
        type="text"
        name="username"
        id="username"
        className="box-content w-[16ch] rounded-r-lg border-2 border-gray-600 bg-gray-700 px-3 py-1"
        placeholder="lorenzhohermuth"
        value={search.username}
        onChange={handleUsernameChange}
      />
    </search>
  );
}
