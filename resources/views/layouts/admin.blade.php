<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin') — charity-hub Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #94a3b8;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #f1f5f9;
        }

        .sidebar-link.active {
            background: rgba(88, 86, 214, 0.2);
            color: #a5a4f9;
            border-left: 3px solid #5856d6;
        }

        .sidebar-link svg {
            flex-shrink: 0;
            width: 1.125rem;
            height: 1.125rem;
        }

        .admin-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            padding: 1.5rem;
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

        .admin-badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .admin-badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .admin-badge-danger {
            background: #fee2e2;
            color: #7f1d1d;
        }

        .admin-badge-info {
            background: #dbeafe;
            color: #1e40af;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-950 text-gray-100 min-h-screen flex">

    <!-- ═══════════════════════════════════════════ -->
    <!-- SIDEBAR -->
    <!-- ═══════════════════════════════════════════ -->
    <aside class="fixed inset-y-0 left-0 w-64 bg-gray-900 border-r border-white/5 flex flex-col z-40 shadow-2xl">

        <!-- LOGO -->
        <div class="px-6 py-5 border-b border-white/5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('images/charity-hub-logo.png') }}"
                    alt="CharityHub" class="h-8 w-8 rounded-lg object-cover">
                <div>
                    <span class="font-bold text-white text-sm tracking-tight">charity<span class="text-primary-400">hub</span></span>
                    <p class="text-xs text-primary-400 font-medium -mt-0.5">Admin Portal</p>
                </div>
            </a>
        </div>

        <!-- NAVIGATION -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

            <p class="px-3 py-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Overview</p>

            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <p class="px-3 py-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">Management</p>

            <a href="{{ route('admin.campaigns.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.campaigns.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Campaigns
            </a>

            <a href="{{ route('admin.donations.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Donations
            </a>

            <a href="{{ route('admin.volunteers.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.volunteers.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Volunteers
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Users
            </a>

            <p class="px-3 py-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">Reports</p>

            <a href="{{ route('admin.reports.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Reports
            </a>

        </nav>

        <!-- BOTTOM: User info + logout -->
        <div class="px-4 py-4 border-t border-white/5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('dashboard') }}"
                    class="flex-1 text-center text-xs py-1.5 rounded-lg border border-gray-700 text-gray-400 hover:text-white hover:border-gray-500 transition">
                    User Portal
                </a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full text-xs py-1.5 rounded-lg bg-red-900/30 text-red-400 hover:bg-red-900/50 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </aside>

    <!-- ═══════════════════════════════════════════ -->
    <!-- MAIN CONTENT AREA -->
    <!-- ═══════════════════════════════════════════ -->
    <div class="flex-1 ml-64 flex flex-col min-h-screen">

        <!-- TOP BAR -->
        <header class="sticky top-0 z-30 bg-gray-900/80 backdrop-blur border-b border-white/5 px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-white">@yield('page-title', 'Admin Dashboard')</h1>
                    <p class="text-xs text-gray-400 mt-0.5">@yield('page-subtitle', 'charity-hub Admin Panel')</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs px-2.5 py-1 rounded-full bg-primary-600/20 text-primary-300 font-medium">
                        Administrator
                    </span>
                    <span class="text-xs text-gray-500">{{ now()->format('M d, Y') }}</span>
                </div>
            </div>
        </header>

        <!-- FLASH MESSAGES -->
        @if(session('success') || session('error') || session('warning'))
        <div class="px-8 pt-4">
            @if(session('success'))
            <div class="flash-message mb-3 px-5 py-3.5 rounded-xl bg-emerald-900/40 border border-emerald-500/30 text-emerald-300 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100 transition">✕</button>
            </div>
            @endif
            @if(session('error'))
            <div class="flash-message mb-3 px-5 py-3.5 rounded-xl bg-red-900/40 border border-red-500/30 text-red-300 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100 transition">✕</button>
            </div>
            @endif
            @if(session('warning'))
            <div class="flash-message mb-3 px-5 py-3.5 rounded-xl bg-amber-900/40 border border-amber-500/30 text-amber-300 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('warning') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100 transition">✕</button>
            </div>
            @endif
        </div>
        @endif

        <!-- PAGE CONTENT -->
        <main class="flex-1 p-8">
            {{ $slot }}
        </main>

        <!-- FOOTER -->
        <footer class="px-8 py-4 border-t border-white/5 text-xs text-gray-600 flex justify-between">
            <span>charity-hub Admin Portal</span>
            <span>&copy; {{ date('Y') }} CharityHub</span>
        </footer>

    </div>

</body>

</html>