<div x-data="projectSearch({{ json_encode($projects) }})">
    <!-- Search Bar -->
    <div class="mx-auto my-10 flex max-w-4xl flex-col gap-3 px-4 font-mono text-white sm:flex-row sm:flex-wrap sm:items-center sm:justify-center">
        <!-- Module + Teacher -->
        <div class="flex items-center">
            <input
                type="number"
                x-model="search.module"
                @input="handleSearch"
                placeholder="431"
                maxlength="3"
                class="box-content w-[3ch] rounded-l-lg border-2 border-gray-600 bg-gray-700 px-3 py-1 outline-none"
            >
            <span class="mx-1">-</span>
            <input
                type="text"
                x-model="search.teacher"
                @input="handleSearch"
                placeholder="ober"
                maxlength="4"
                class="box-content w-[4ch] rounded-r-lg border-2 border-gray-600 bg-gray-700 px-3 py-1 outline-none"
            >
        </div>

        <!-- Project Name -->
        <div class="flex items-center">
            <input
                type="text"
                x-model="search.name"
                @input="handleSearch"
                placeholder="Linux Cookbook"
                class="box-content w-full min-w-0 rounded-lg border-2 border-gray-600 bg-gray-700 px-3 py-1 outline-none sm:w-[32ch]"
            >
        </div>

        <!-- Username -->
        <div class="flex items-center">
            <input
                type="text"
                x-model="search.username"
                @input="handleSearch"
                placeholder="lorenzhohermuth"
                maxlength="39"
                class="box-content w-full min-w-0 rounded-lg border-2 border-gray-600 bg-gray-700 px-3 py-1 outline-none sm:w-[16ch]"
            >
        </div>
    </div>

    <!-- Results Count -->
    <p class="mb-1 italic text-gray-500 max-w-4xl mx-auto">
        Showing <span class="font-bold" x-text="searchedProjects.length"></span> results
    </p>

    <!-- Projects List -->
    <ul class="mx-auto flex max-w-4xl flex-col gap-3">
        <template x-for="project in searchedProjects" :key="project.id">
            <li class="flex items-center justify-between rounded-lg border border-gray-700 bg-gray-800 p-4 shadow-sm shadow-gray-700 transition-colors hover:border-bbw-400">
                <div class="flex-1 min-w-0">
                    <h3 class="mb-1 font-mono text-base text-white">
                        <span class="text-bbw-400" x-text="project.module"></span>-<span x-text="project.teacher"></span>-<span x-text="project.slugified_task_name"></span>-<span x-text="project.username"></span>
                    </h3>
                    <p class="text-sm text-gray-400" x-text="project.task_name"></p>
                </div>
                <a :href="project.html_url" target="_blank" class="ml-4 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gray-700 text-gray-300 transition-colors duration-100 hover:bg-gray-600">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                </a>
            </li>
        </template>
    </ul>

    <!-- No Results -->
    <div x-show="searchedProjects.length === 0" class="py-12 text-center text-gray-400">
        <p>No projects found matching your search criteria.</p>
    </div>
</div>

<script>
function projectSearch(projects) {
    return {
        projects: projects,
        searchedProjects: projects,
        search: {
            module: '',
            teacher: '',
            name: '',
            username: ''
        },
        searchTimeout: null,

        init() {
            this.searchedProjects = this.projects;
        },

        handleSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.performSearch();
            }, 250);
        },

        performSearch() {
            // If all search fields are empty, show all results
            if (!this.search.module && !this.search.teacher && !this.search.name && !this.search.username) {
                this.searchedProjects = this.projects;
                return;
            }

            // Filter projects based on search criteria
            this.searchedProjects = this.projects.filter(project => {
                const moduleMatch = !this.search.module || 
                    project.module.toLowerCase().includes(this.search.module.toLowerCase());
                
                const teacherMatch = !this.search.teacher || 
                    project.teacher.toLowerCase().includes(this.search.teacher.toLowerCase());
                
                const nameMatch = !this.search.name || 
                    project.task_name.toLowerCase().includes(this.search.name.toLowerCase()) ||
                    project.slugified_task_name.toLowerCase().includes(this.search.name.toLowerCase());
                
                const usernameMatch = !this.search.username || 
                    project.username.toLowerCase().includes(this.search.username.toLowerCase());

                return moduleMatch && teacherMatch && nameMatch && usernameMatch;
            });
        }
    };
}
</script>
