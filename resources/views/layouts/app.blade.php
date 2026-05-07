<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }

        .nav-link {
            position: relative;
            padding: 0.25rem 0;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
            transition: color 0.2s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            border-radius: 2px;
            transition: width 0.25s ease;
        }
        .nav-link:hover { color: #1e40af; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .nav-link.active { color: #1e40af; font-weight: 600; }

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
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .flash-message { animation: slideDown 0.3s ease both; }

        /* Mobile menu */
        #mobile-menu {
            display: none;
        }
        #mobile-menu.open {
            display: block;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">

    <!-- ═══════════════════════════════════════════ -->
    <!-- NAVBAR -->
    <!-- ═══════════════════════════════════════════ -->
    <nav class="sticky top-0 z-50 backdrop-blur-xl bg-white/80 border-b border-white/60 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- LOGO -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group flex-shrink-0">
                    <img src="{{ asset('images/charity-hub-logo.png') }}"
                         alt="CharityHub"
                         class="h-8 w-8 rounded-lg object-cover shadow-sm group-hover:shadow-md transition-shadow">
                    <span class="font-bold text-lg text-blue-900 tracking-tight">
                        charity<span class="text-blue-500">hub</span>
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

                    <a href="{{ route('donations.track') }}"
                       class="nav-link {{ request()->routeIs('donations.track*') ? 'active' : '' }}">
                        Track Donation
                    </a>

                    @auth
                        <a href="{{ route('donations.my') }}"
                           class="nav-link {{ request()->routeIs('donations.my') ? 'active' : '' }}">
                            My Donations
                        </a>
                    @endauth

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 10a8 8 0 1116 0A8 8 0 012 10zm5-2a3 3 0 106 0 3 3 0 00-6 0zm-1 8a6 6 0 0112 0H6z"/>
                                </svg>
                                Admin
                            </a>
                        @endif
                    @endauth

                </div>

                <!-- RIGHT SIDE: USER + LOGOUT -->
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <span class="text-sm text-gray-600 font-medium">
                            {{ auth()->user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="text-sm px-4 py-1.5 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 hover:border-red-300 transition font-medium">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm text-gray-600 hover:text-blue-700 font-medium transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="text-sm px-4 py-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition font-medium">
                            Sign Up
                        </a>
                    @endauth
                </div>

                <!-- MOBILE HAMBURGER -->
                <button onclick="document.getElementById('mobile-menu').classList.toggle('open')"
                        class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

            </div>
        </div>

        <!-- MOBILE MENU -->
        <div id="mobile-menu" class="md:hidden border-t border-gray-100 bg-white/95 backdrop-blur-xl px-4 py-3 space-y-2">
            <a href="{{ route('dashboard') }}" class="block py-2 text-sm font-medium text-gray-700 hover:text-blue-600">Dashboard</a>
            <a href="{{ route('campaigns.index') }}" class="block py-2 text-sm font-medium text-gray-700 hover:text-blue-600">Campaigns</a>
            <a href="{{ route('volunteers.index') }}" class="block py-2 text-sm font-medium text-gray-700 hover:text-blue-600">Volunteers</a>
            <a href="{{ route('donations.track') }}" class="block py-2 text-sm font-medium text-gray-700 hover:text-blue-600">Track Donation</a>
            @auth
                <a href="{{ route('donations.my') }}" class="block py-2 text-sm font-medium text-gray-700 hover:text-blue-600">My Donations</a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block py-2 text-sm font-semibold text-blue-600">Admin Portal</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-gray-100">
                    @csrf
                    <button type="submit" class="text-sm text-red-500 font-medium">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block py-2 text-sm text-gray-700">Login</a>
                <a href="{{ route('register') }}" class="block py-2 text-sm text-blue-600 font-semibold">Sign Up</a>
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
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
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
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
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
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
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
    <footer class="bg-white/60 backdrop-blur border-t border-gray-100 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/charity-hub-logo.png') }}" alt="CharityHub" class="h-6 w-6 rounded object-cover">
                    <span class="font-bold text-gray-700">charity<span class="text-blue-500">hub</span></span>
                </div>
                <p class="text-sm text-gray-400">
                    &copy; {{ date('Y') }} CharityHub — Nonprofit Donation &amp; Campaign Platform
                </p>
                <div class="flex gap-4 text-sm text-gray-400">
                    <a href="{{ route('donations.track') }}" class="hover:text-blue-600 transition">Track Donation</a>
                    <a href="{{ route('campaigns.index') }}" class="hover:text-blue-600 transition">Campaigns</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>