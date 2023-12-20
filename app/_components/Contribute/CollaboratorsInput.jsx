import { useState } from "react";
import { FaXmark } from "react-icons/fa6";

export default function CollaboratorsInput({ key, collaborators, setCollaborators }) {
  const [newCollaborator, setNewCollaborator] = useState("");
  const [error, setError] = useState(null)

  const handleCollaboratorInput = (e, enterPressed) => {
    setError(null)

    // If whitespace or , pressed add new collaborator to state
    if (
      e.target.value[e.target.value.length - 1] === " " ||
      e.target.value[e.target.value.length - 1] === "," || enterPressed
    ) {
      const checkIfNewCollaboratorIsUnique = collaborators.findIndex(collab => collab.toLowerCase() === newCollaborator.toLowerCase())
      // Already in array
      if (checkIfNewCollaboratorIsUnique !== -1) {
        setError("Username already exists")
      } else if (!/^[a-z\d](?:[a-z\d]|-(?=[a-z\d])){0,38}$/i.test(newCollaborator)) {
        setError("Username is invalid. Please check for special characters or whitespaces.")
      }
      else {
        collaborators.push(newCollaborator);
        setCollaborators(collaborators);
        setNewCollaborator("");
      }
    } else {
      setNewCollaborator(e.target.value);
    }
  };

  function handleCollaboratorRemove(e, collaborator) {
    e.preventDefault();
    setCollaborators(
      collaborators.filter(function (coll) {
        return coll !== collaborator;
      })
    );
  }

  const handleKeyDown = (e) => {
    if (e.key === "Enter") {
      e.preventDefault()
      handleCollaboratorInput(e, true);
    }

    if (e.key === "Backspace" && newCollaborator.length === 0) {
      setCollaborators(collaborators.slice(0, collaborators.length - 1))
    }
  }

  return (
    <div>
      <label className="text-lg">Collaborators (github usernames)</label>
      <ul className="flex flex-wrap items-center gap-2 rounded-lg border border-gray-500 p-4">
        {collaborators.map((collaborator) => {
          return (
            <li
              className="flex items-center gap-2 rounded-md bg-gray-700 py-2 pl-4 pr-3"
              key={collaborator}
            >
              {collaborator}
              <button
                onClick={(e) => handleCollaboratorRemove(e, collaborator)}
                className="rounded-full bg-gray-500 p-1"
                tabIndex={-1}
              >
                <FaXmark className="text-xs" />
              </button>
            </li>
          );
        })}
        <li>
          <input
            className="box-content bg-transparent font-mono text-white text-sm outline-none"
            style={{
              width: `${newCollaborator.length === 0
                ? 16
                : newCollaborator.length < 6
                  ? 6
                  : newCollaborator.length
                }ch`,
            }}
            placeholder="Enter username.."
            value={newCollaborator}
            onChange={handleCollaboratorInput}
            onKeyDown={handleKeyDown}
          />
        </li>
      </ul>
      <p className="text-gray-500 italic">Type space or comma to add username. Press enter to delete first added.</p>
      {error && <span className="text-red-400">{error}</span>}
    </div>
  );
}
