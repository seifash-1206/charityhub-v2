<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CharityHub') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900">

<div class="min-h-screen bg-gradient-to-br from-white via-blue-50 to-blue-200 relative overflow-hidden">

    <!-- NAVBAR -->
    <nav class="relative z-10 backdrop-blur-lg bg-white/70 border-b border-white/40 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <a href="/dashboard" class="flex items-center gap-2 text-blue-900 font-bold text-lg">
                🚀 CharityHub
            </a>

            <div class="flex items-center gap-6">
                <a href="/dashboard" class="text-gray-600 hover:text-blue-900">Dashboard</a>
                <a href="/campaigns" class="text-gray-600 hover:text-blue-900">Campaigns</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-500 hover:text-red-700">Logout</button>
                </form>
            </div>

        </div>
    </nav>

    <!-- ✅ ONLY THIS (IMPORTANT) -->
    <main class="relative z-10 p-8">
        {{ $slot }}
    </main>

</div>

</body>
</html>