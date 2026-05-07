<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 bg-gradient-to-br from-white via-blue-50 to-indigo-100 min-h-screen">

    <!-- BACKGROUND BLOBS -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute w-[500px] h-[500px] bg-blue-300/30 blur-[100px] rounded-full top-[-100px] left-[-100px]"></div>
        <div class="absolute w-[400px] h-[400px] bg-indigo-300/20 blur-[100px] rounded-full bottom-[-50px] right-[-50px]"></div>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center relative z-10 p-4">

        <!-- LOGO -->
        <div class="mb-6">
            <a href="/" class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center overflow-hidden p-2">
                    <img src="{{ asset('images/charity-hub-logo.png') }}" alt="CharityHub Logo" class="w-full h-full object-contain">
                </div>
                <span class="text-2xl font-bold text-gray-900 tracking-tight">Charity<span class="text-blue-600">Hub</span></span>
            </a>
        </div>

        <!-- GLASS CARD -->
        <div class="w-full sm:max-w-md px-8 py-8 
            bg-white/80 backdrop-blur-xl 
            border border-white/50 
            shadow-2xl 
            rounded-3xl">

            {{ $slot }}

        </div>

    </div>

</body>
</html>