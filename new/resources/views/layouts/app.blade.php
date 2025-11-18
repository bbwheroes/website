<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BBW Heroes - Where BBW students come together')</title>
    <meta name="description" content="Where BBW students come together to form a community.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-900">
    <div class="border-b border-gray-800">
        <nav class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-4 font-medium md:px-12">
            <a href="{{ route('home') }}">
                <img src="{{ asset('bbwheroes.svg') }}" alt="BBW Heroes Logo" class="h-16 w-auto md:h-20">
            </a>
            <ul class="flex items-center gap-4 md:gap-8">
                <li>
                    <a href="{{ route('projects') }}" class="text-gray-300 duration-100 hover:text-white">
                        Projects
                    </a>
                </li>
                <li>
                    <a href="https://discord.gg/xbUfU4FYSc" target="_blank" class="flex items-center gap-1 text-gray-300 duration-100 hover:text-white">
                        Discord
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path>
                            <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <a href="{{ route('contribute') }}" class="rounded-lg bg-bbw-400 px-3 py-1.5 text-gray-900 duration-100 hover:bg-bbw-500">
                        Contribute
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <main>
        @if(session('success'))
            <div class="mx-auto max-w-5xl px-6 pt-4 md:px-12">
                <div class="rounded-lg bg-green-500/10 border border-green-500/20 px-4 py-3 text-green-400">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="mt-16 border-t border-gray-800 bg-gray-950 py-8">
        <div class="mx-auto max-w-5xl px-6 md:px-12">
            <div class="mb-6 text-center">
                <a href="{{ route('filament.admin.auth.login') }}" class="text-sm text-gray-500 hover:text-bbw-400 duration-100">
                    Admin Panel
                </a>
            </div>
            <div class="text-center text-sm text-gray-500">
                <p class="mb-2">
                    <strong>Disclaimer:</strong> Our intention and main goal is to create a platform to share and grow together. 
                    However, it is not our responsibility how the resources are used. Just be sure to follow the licensing, 
                    if any, for the respective resource and code and do not sell anything as yours that is not made by you.
                </p>
                <p class="text-gray-600">
                    &copy; {{ date('Y') }} BBW Heroes. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
