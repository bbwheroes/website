@extends('layouts.app')

@section('title', 'Contribute - BBW Heroes')

@section('content')
<section class="mx-auto max-w-3xl px-6 py-16 md:px-12">
    <h1 class="mb-4 text-center text-3xl text-white md:text-4xl">Create a proposal</h1>
    <p class="my-12 text-gray-400">
        We appreciate that you want to contribute to this network. Please fill out this quick
        proposal and after a quick review you should get notified. After an
        approval we run every necessary process to create your environment.
        <a href="https://github.com/bbwheroes/system/tree/main/processes" target="_blank" class="text-bbw-400 underline duration-100 hover:text-bbw-500">
            More information here.
        </a>
    </p>

    <form action="{{ route('contribute.store') }}" method="POST" class="flex flex-col gap-6 text-white" x-data="contributeForm()">
        @csrf

        <!-- Module Number -->
        <div>
            <label for="module" class="mb-2 block text-sm font-medium">
                Module number <span class="text-red-400">*</span>
            </label>
            <input
                type="number"
                id="module"
                name="module"
                x-model="form.module"
                @input="validateModule"
                placeholder="431"
                maxlength="3"
                required
                class="w-full rounded-lg border-2 border-gray-600 bg-gray-700 px-4 py-2 outline-none focus:border-bbw-400"
            >
            <p x-show="errors.module" x-text="errors.module" class="mt-1 text-sm text-red-400"></p>
            @error('module')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Teacher -->
        <div>
            <label for="teacher" class="mb-2 block text-sm font-medium">
                Teacher (first 4 letters only) <span class="text-red-400">*</span>
            </label>
            <input
                type="text"
                id="teacher"
                name="teacher"
                x-model="form.teacher"
                @input="validateTeacher"
                placeholder="ober"
                maxlength="4"
                required
                class="w-full rounded-lg border-2 border-gray-600 bg-gray-700 px-4 py-2 outline-none focus:border-bbw-400"
            >
            <p x-show="errors.teacher" x-text="errors.teacher" class="mt-1 text-sm text-red-400"></p>
            @error('teacher')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Task Name -->
        <div>
            <label for="task_name" class="mb-2 block text-sm font-medium">
                Complete task name <span class="text-red-400">*</span>
            </label>
            <input
                type="text"
                id="task_name"
                name="task_name"
                x-model="form.task_name"
                @input="updateSlugifiedName"
                placeholder="Linux Cookbook"
                required
                class="w-full rounded-lg border-2 border-gray-600 bg-gray-700 px-4 py-2 outline-none focus:border-bbw-400"
            >
            @error('task_name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Slugified Task Name -->
        <div>
            <label for="slugified_task_name" class="mb-2 block text-sm font-medium">
                Slugified task name (auto-generated)
            </label>
            <input
                type="text"
                id="slugified_task_name"
                x-model="slugifiedTaskName"
                placeholder="linux_cookbook"
                readonly
                class="w-full rounded-lg border-2 border-gray-600 bg-gray-800 px-4 py-2 text-gray-400 outline-none"
            >
        </div>

        <!-- GitHub Username -->
        <div>
            <label for="github_username" class="mb-2 block text-sm font-medium">
                GitHub username <span class="text-red-400">*</span>
            </label>
            <input
                type="text"
                id="github_username"
                name="github_username"
                x-model="form.github_username"
                @input="validateGithubUsername"
                placeholder="lorenzhohermuth"
                maxlength="39"
                required
                class="w-full rounded-lg border-2 border-gray-600 bg-gray-700 px-4 py-2 outline-none focus:border-bbw-400"
            >
            <p x-show="errors.github_username" x-text="errors.github_username" class="mt-1 text-sm text-red-400"></p>
            @error('github_username')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Collaborators -->
        <div>
            <label class="mb-2 block text-sm font-medium">
                Collaborators (optional)
            </label>
            <div class="space-y-2">
                <template x-for="(collaborator, index) in form.collaborators" :key="index">
                    <div class="flex gap-2">
                        <input
                            type="text"
                            :name="'collaborators[' + index + ']'"
                            x-model="form.collaborators[index]"
                            placeholder="GitHub username"
                            maxlength="39"
                            class="flex-1 rounded-lg border-2 border-gray-600 bg-gray-700 px-4 py-2 outline-none focus:border-bbw-400"
                        >
                        <button
                            type="button"
                            @click="removeCollaborator(index)"
                            class="rounded-lg bg-red-600 px-4 py-2 hover:bg-red-700"
                        >
                            Remove
                        </button>
                    </div>
                </template>
                <button
                    type="button"
                    @click="addCollaborator"
                    class="rounded-lg bg-gray-600 px-4 py-2 hover:bg-gray-700"
                >
                    + Add Collaborator
                </button>
            </div>
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            :disabled="!isValid"
            :class="isValid ? 'hover:bg-bbw-500 cursor-pointer' : 'opacity-50 cursor-not-allowed'"
            class="mx-auto w-min rounded-md bg-bbw-400 px-4 py-2 font-medium text-gray-900 duration-100"
        >
            Submit
        </button>
    </form>
</section>

<script>
function contributeForm() {
    return {
        form: {
            module: '',
            teacher: '',
            task_name: '',
            github_username: '',
            collaborators: []
        },
        errors: {
            module: '',
            teacher: '',
            github_username: ''
        },
        slugifiedTaskName: '',
        isValid: false,

        validateModule() {
            const module = this.form.module;
            if (!module || module.length !== 3) {
                this.errors.module = 'Module must be exactly 3 digits';
            } else {
                this.errors.module = '';
            }
            this.checkValidity();
        },

        validateTeacher() {
            const teacher = this.form.teacher;
            if (!teacher || teacher.length !== 4) {
                this.errors.teacher = 'Teacher must be exactly 4 letters';
            } else {
                this.errors.teacher = '';
            }
            this.checkValidity();
        },

        validateGithubUsername() {
            const username = this.form.github_username;
            if (!username || username.length === 0) {
                this.errors.github_username = 'GitHub username is required';
            } else if (username.length > 39) {
                this.errors.github_username = 'GitHub username is too long';
            } else {
                this.errors.github_username = '';
            }
            this.checkValidity();
        },

        updateSlugifiedName() {
            this.slugifiedTaskName = this.form.task_name
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '_')
                .replace(/^_+|_+$/g, '');
            this.checkValidity();
        },

        addCollaborator() {
            this.form.collaborators.push('');
        },

        removeCollaborator(index) {
            this.form.collaborators.splice(index, 1);
        },

        checkValidity() {
            this.isValid = 
                this.form.module.length === 3 &&
                this.form.teacher.length === 4 &&
                this.form.task_name.length > 0 &&
                this.form.github_username.length > 0 &&
                !this.errors.module &&
                !this.errors.teacher &&
                !this.errors.github_username;
        }
    };
}
</script>
@endsection
