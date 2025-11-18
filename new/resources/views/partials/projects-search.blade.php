<div x-data="projectSearch({{ json_encode($projects) }}, {{ $limit ?? 10 }})">
    <!-- Search Bar -->
    <div class="mx-auto my-10 flex max-w-max items-center font-mono text-white">
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
            class="box-content w-[4ch] border-2 border-gray-600 bg-gray-700 px-3 py-1 outline-none"
        >
        <span class="mx-1">-</span>
        <input
            type="text"
            x-model="search.name"
            @input="handleSearch"
            placeholder="Linux Cookbook"
            class="box-content w-[32ch] border-2 border-gray-600 bg-gray-700 px-3 py-1 outline-none"
        >
        <span class="mx-1">-</span>
        <input
            type="text"
            x-model="search.username"
            @input="handleSearch"
            placeholder="lorenzhohermuth"
            maxlength="39"
            class="box-content w-[16ch] rounded-r-lg border-2 border-gray-600 bg-gray-700 px-3 py-1 outline-none"
        >
    </div>

    <!-- Results Count -->
    <p class="mb-1 italic text-gray-500">
        Showing <span class="font-bold" x-text="searchedProjects.length"></span> results
    </p>

    <!-- Projects List -->
    <ul class="flex flex-col gap-4">
        <template x-for="project in searchedProjects" :key="project.id">
            <li class="rounded-lg border border-gray-700 bg-gray-800 p-4 transition-colors hover:border-bbw-400">
                <a :href="project.html_url" target="_blank" class="block">
                    <div class="mb-2 flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="mb-1 font-mono text-lg text-white">
                                <span class="text-bbw-400" x-text="project.module"></span>-<span x-text="project.teacher"></span>-<span x-text="project.slugified_task_name"></span>-<span x-text="project.username"></span>
                            </h3>
                            <p class="text-gray-300" x-text="project.task_name"></p>
                        </div>
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path>
                            <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-400" x-text="project.description || 'No description'"></p>
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
function projectSearch(projects, limit) {
    return {
        projects: projects,
        searchedProjects: projects.slice(0, limit),
        search: {
            module: '',
            teacher: '',
            name: '',
            username: ''
        },
        searchTimeout: null,

        init() {
            this.searchedProjects = this.projects.slice(0, limit);
        },

        handleSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.performSearch();
            }, 250);
        },

        performSearch() {
            // If all search fields are empty, show limited results
            if (!this.search.module && !this.search.teacher && !this.search.name && !this.search.username) {
                this.searchedProjects = this.projects.slice(0, limit);
                return;
            }

            // Filter projects based on search criteria
            const filtered = this.projects.filter(project => {
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

            this.searchedProjects = filtered.slice(0, limit);
        }
    };
}
</script>
