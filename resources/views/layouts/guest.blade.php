<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CharityHub') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900">

    <!-- BACKGROUND -->
    <div class="min-h-screen flex flex-col items-center justify-center 
        bg-gradient-to-br from-white via-blue-50 to-blue-200 relative overflow-hidden">

        <!-- soft glass blobs -->
        <div class="absolute w-96 h-96 bg-blue-300 opacity-30 blur-3xl rounded-full top-10 left-10"></div>
        <div class="absolute w-96 h-96 bg-red-300 opacity-20 blur-3xl rounded-full bottom-10 right-10"></div>

        <!-- LOGO -->
        <div class="relative z-10">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-blue-900 opacity-80" />
            </a>
        </div>

        <!-- GLASS CARD -->
        <div class="relative z-10 w-full sm:max-w-md mt-6 px-8 py-6 
            bg-white/70 backdrop-blur-xl 
            border border-white/40 
            shadow-2xl 
            rounded-2xl">

            {{ $slot }}

        </div>
    </div>

</body>
</html>