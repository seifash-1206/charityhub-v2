<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CharityHub') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="font-sans antialiased text-secondary-900 dark:text-secondary-100 bg-gradient-to-br from-primary-50 via-primary-100 to-secondary-50 dark:from-secondary-950 dark:via-secondary-900 dark:to-secondary-900 min-h-screen transition-colors">

    <!-- BACKGROUND BLOBS -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute w-[500px] h-[500px] bg-primary-400/20 dark:bg-primary-900/40 blur-[100px] rounded-full top-[-100px] left-[-100px]"></div>
        <div class="absolute w-[400px] h-[400px] bg-primary-300/10 dark:bg-secondary-900/50 blur-[100px] rounded-full bottom-[-50px] right-[-50px]"></div>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center relative z-10 p-4">

        <!-- HEADER WITH THEME TOGGLE -->
        <div class="absolute top-6 right-6">
            <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light'); document.documentElement.classList.toggle('dark')"
                class="p-2 rounded-lg bg-white/80 dark:bg-secondary-800/80 backdrop-blur-sm border border-primary-200/50 dark:border-secondary-700/50 text-primary-900 dark:text-primary-400 hover:bg-white dark:hover:bg-secondary-700/80 transition-all"
                aria-label="Toggle theme">
                <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                </svg>
                <svg x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.536a1 1 0 11.707-1.414l.707.707a1 1 0 11-.707 1.414zm2.12-10.607a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-.707l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm5.657-9.193a1 1 0 00-1.414 0l-.707.707A1 1 0 005.05 3.536l.707-.707a1 1 0 001.414 0zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- LOGO -->
        <div class="mb-8">
            <a href="/" class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-white dark:bg-secondary-800 rounded-2xl shadow-lg dark:shadow-secondary-900/50 flex items-center justify-center overflow-hidden p-2 border border-primary-100 dark:border-secondary-700/50">
                    <img src="{{ asset('images/charity-hub-logo.png') }}" alt="CharityHub Logo" class="w-full h-full object-contain">
                </div>
                <span class="text-3xl font-bold text-primary-900 dark:text-white tracking-tight">Charity<span class="text-primary-700 dark:text-primary-400">Hub</span></span>
            </a>
        </div>

        <!-- GLASS CARD -->
        <div class="w-full sm:max-w-md px-8 py-8
            bg-white/80 dark:bg-secondary-800/40
            backdrop-blur-2xl
            border border-white/50 dark:border-secondary-700/30
            shadow-2xl dark:shadow-secondary-900/50
            rounded-3xl">

            {{ $slot }}

        </div>

    </div>

</body>

</html>