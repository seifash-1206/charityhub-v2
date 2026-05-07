<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'CharityHub'))</title>
    <meta name="description" content="@yield('meta_description', 'CharityHub — Donate, volunteer, and make a difference.')">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.tsx'])

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .nav-link {
            position: relative;
            padding: 0.25rem 0;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
            transition: color 0.2s ease;
        }

        .dark .nav-link {
            color: #9ca3af;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(to right, #0c2340, #1e3a8a);
            border-radius: 2px;
            transition: width 0.25s ease;
        }

        .nav-link:hover {
            color: #0c2340;
        }

        .dark .nav-link:hover {
            color: #60a5fa;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link.active {
            color: #0c2340;
            font-weight: 600;
        }

        .dark .nav-link.active {
            color: #60a5fa;
        }

        .flash-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border-left: 4px solid #10b981;
            color: #065f46;
        }

        .flash-error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border-left: 4px solid #ef4444;
            color: #7f1d1d;
        }

        .flash-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-left: 4px solid #f59e0b;
            color: #78350f;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .flash-message {
            animation: slideDown 0.3s ease both;
        }

        /* Mobile menu */
        #mobile-menu {
            display: none;
        }

        #mobile-menu.open {
            display: block;
        }

        .theme-toggle {
            @apply p-2 rounded-lg bg-primary-100 dark:bg-secondary-700 text-primary-900 dark:text-primary-300 hover:bg-primary-200 dark:hover:bg-secondary-600 transition-colors;
        }
    </style>
</head>

<body class="font-sans antialiased text-secondary-900 dark:text-secondary-100 bg-gradient-to-br from-primary-50 via-primary-100 to-secondary-100 dark:from-secondary-900 dark:via-secondary-800 dark:to-secondary-900 min-h-screen transition-colors">

    <!-- ═══════════════════════════════════════════ -->
    <!-- NAVBAR -->
    <!-- ═══════════════════════════════════════════ -->
    <nav class="sticky top-0 z-50 backdrop-blur-xl bg-white/80 dark:bg-secondary-900/80 border-b border-secondary-200 dark:border-secondary-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- LOGO -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group flex-shrink-0">
                    <img src="{{ asset('images/charity-hub-logo.png') }}"
                        alt="CharityHub"
                        class="h-8 w-8 rounded-lg object-cover shadow-sm group-hover:shadow-md transition-shadow">
                    <span class="font-bold text-lg text-primary-900 dark:text-white tracking-tight">
                        charity<span class="text-primary-700 dark:text-primary-400">hub</span>
                    </span>
                </a>

                <!-- DESKTOP NAV LINKS -->
                <div class="hidden md:flex items-center gap-7">

                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('campaigns.index') }}"
                        class="nav-link {{ request()->routeIs('campaigns.*') ? 'active' : '' }}">
                        Campaigns
                    </a>

                    <a href="{{ route('volunteers.index') }}"
                        class="nav-link {{ request()->routeIs('volunteers.*') ? 'active' : '' }}">
                        Volunteers
                    </a>

                    @if(auth()->user() && auth()->user()->role === 'admin')
                    <a href="{{ route('donations.track') }}"
                        class="nav-link {{ request()->routeIs('donations.track*') ? 'active' : '' }}">
                        Track Donation
                    </a>
                    @endif

                    @auth
                    <a href="{{ route('donations.my') }}"
                        class="nav-link {{ request()->routeIs('donations.my') ? 'active' : '' }}">
                        My Donations
                    </a>
                    @endauth

                    @auth
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-900 dark:bg-primary-800 text-white text-sm font-semibold hover:bg-primary-800 dark:hover:bg-primary-700 transition shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 1116 0A8 8 0 012 10zm5-2a3 3 0 106 0 3 3 0 00-6 0zm-1 8a6 6 0 0112 0H6z" />
                        </svg>
                        Admin
                    </a>
                    @endif
                    @endauth

                </div>

                <!-- RIGHT SIDE: USER + LOGOUT + THEME TOGGLE -->
                <div class="flex items-center gap-4">
                    <!-- Theme Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light'); document.documentElement.classList.toggle('dark')"
                        class="theme-toggle hidden sm:block"
                        aria-label="Toggle theme">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                        </svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.536a1 1 0 11.707-1.414l.707.707a1 1 0 11-.707 1.414zm2.12-10.607a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-.707l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm5.657-9.193a1 1 0 00-1.414 0l-.707.707A1 1 0 005.05 3.536l.707-.707a1 1 0 001.414 0zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    @auth
                    <span class="hidden md:inline text-sm text-secondary-600 dark:text-secondary-300 font-medium">
                        {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="hidden md:inline">
                        @csrf
                        <button type="submit"
                            class="text-sm px-4 py-1.5 rounded-lg border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-300 dark:hover:border-red-700 transition font-medium">
                            Logout
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}"
                        class="hidden md:inline text-sm text-secondary-600 dark:text-secondary-300 hover:text-primary-900 dark:hover:text-primary-400 font-medium transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="hidden md:inline text-sm px-4 py-1.5 rounded-lg bg-primary-900 dark:bg-primary-800 text-white hover:bg-primary-800 dark:hover:bg-primary-700 transition font-medium">
                        Sign Up
                    </a>
                    @endauth

                    <!-- MOBILE HAMBURGER -->
                    <button onclick="document.getElementById('mobile-menu').classList.toggle('open')"
                        class="md:hidden p-2 rounded-lg text-secondary-600 dark:text-secondary-300 hover:bg-secondary-100 dark:hover:bg-secondary-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- MOBILE MENU -->
        <div id="mobile-menu" class="md:hidden border-t border-secondary-200 dark:border-secondary-800 bg-white/95 dark:bg-secondary-800/95 backdrop-blur-xl px-4 py-3 space-y-2">
            <a href="{{ route('dashboard') }}" class="block py-2 text-sm font-medium text-secondary-700 dark:text-secondary-300 hover:text-primary-900 dark:hover:text-primary-400">Dashboard</a>
            <a href="{{ route('campaigns.index') }}" class="block py-2 text-sm font-medium text-secondary-700 dark:text-secondary-300 hover:text-primary-900 dark:hover:text-primary-400">Campaigns</a>
            <a href="{{ route('volunteers.index') }}" class="block py-2 text-sm font-medium text-secondary-700 dark:text-secondary-300 hover:text-primary-900 dark:hover:text-primary-400">Volunteers</a>
            @if(auth()->user() && auth()->user()->role === 'admin')
            <a href="{{ route('donations.track') }}" class="block py-2 text-sm font-medium text-secondary-700 dark:text-secondary-300 hover:text-primary-900 dark:hover:text-primary-400">Track Donation</a>
            @endif
            @auth
            <a href="{{ route('donations.my') }}" class="block py-2 text-sm font-medium text-secondary-700 dark:text-secondary-300 hover:text-primary-900 dark:hover:text-primary-400">My Donations</a>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="block py-2 text-sm font-semibold text-primary-900 dark:text-primary-400">Admin Portal</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-secondary-200 dark:border-secondary-700">
                @csrf
                <button type="submit" class="text-sm text-red-600 dark:text-red-400 font-medium">Logout</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="block py-2 text-sm text-secondary-700 dark:text-secondary-300">Login</a>
            <a href="{{ route('register') }}" class="block py-2 text-sm text-primary-900 dark:text-primary-400 font-semibold">Sign Up</a>
            @endauth
        </div>
    </nav>

    <!-- ═══════════════════════════════════════════ -->
    <!-- FLASH MESSAGES -->
    <!-- ═══════════════════════════════════════════ -->
    @if(session('success') || session('error') || session('warning'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        @if(session('success'))
        <div class="flash-message flash-success rounded-xl px-5 py-3.5 shadow-sm flex items-center justify-between mb-3">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-current opacity-60 hover:opacity-100 transition ml-4">✕</button>
        </div>
        @endif
        @if(session('error'))
        <div class="flash-message flash-error rounded-xl px-5 py-3.5 shadow-sm flex items-center justify-between mb-3">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-current opacity-60 hover:opacity-100 transition ml-4">✕</button>
        </div>
        @endif
        @if(session('warning'))
        <div class="flash-message flash-warning rounded-xl px-5 py-3.5 shadow-sm flex items-center justify-between mb-3">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium text-sm">{{ session('warning') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-current opacity-60 hover:opacity-100 transition ml-4">✕</button>
        </div>
        @endif
    </div>
    @endif

    <!-- ═══════════════════════════════════════════ -->
    <!-- MAIN CONTENT -->
    <!-- ═══════════════════════════════════════════ -->
    <main class="min-h-[calc(100vh-4rem)]">
        {{ $slot }}
    </main>

    <!-- ═══════════════════════════════════════════ -->
    <!-- FOOTER -->
    <!-- ═══════════════════════════════════════════ -->
    <footer class="bg-white/60 dark:bg-secondary-800/60 backdrop-blur border-t border-secondary-200 dark:border-secondary-700 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/charity-hub-logo.png') }}" alt="CharityHub" class="h-6 w-6 rounded object-cover">
                    <span class="font-bold text-secondary-700 dark:text-secondary-200">charity<span class="text-primary-700 dark:text-primary-400">hub</span></span>
                </div>
                <p class="text-sm text-secondary-400 dark:text-secondary-500">
                    &copy; {{ date('Y') }} CharityHub — Nonprofit Donation &amp; Campaign Platform
                </p>
                <div class="flex gap-4 text-sm text-secondary-400 dark:text-secondary-500">
                    <a href="{{ route('donations.track') }}" class="hover:text-primary-900 dark:hover:text-primary-400 transition">Track Donation</a>
                    <a href="{{ route('campaigns.index') }}" class="hover:text-primary-900 dark:hover:text-primary-400 transition">Campaigns</a>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>