<x-app-layout>
    @section('title', 'Dashboard — CharityHub')

    <!-- HERO SECTION WITH PARALLAX BACKGROUND -->
    <div class="relative w-full h-80 md:h-96 overflow-hidden bg-gradient-to-br from-primary-900 to-primary-800">
        <!-- Background Image with Parallax Effect -->
        <div class="absolute inset-0 opacity-30"
            style="background-image: url('https://images.unsplash.com/photo-1488521787991-3bc02228340c?w=1200&q=80');
                    background-size: cover;
                    background-position: center;
                    background-attachment: fixed;">
        </div>

        <!-- Dark Overlay Gradient -->
        <div class="absolute inset-0 bg-gradient-to-b from-primary-900/60 via-primary-900/70 to-primary-900/80"></div>

        <!-- Hero Content -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white px-4 max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-3 drop-shadow-lg">
                    Welcome to CharityHub 🎯
                </h1>
                <p class="text-lg md:text-xl text-gray-100 drop-shadow-md">
                    Make a difference. Support causes that matter to you.
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- STATS ROW -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-10">

            <!-- Total Raised -->
            <div class="glass-card relative bg-gradient-to-br from-primary-900 to-primary-800 dark:from-primary-800 dark:to-primary-700 border-0 p-6 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-8 translate-x-8"></div>
                <p class="text-primary-200 dark:text-primary-300 text-xs font-semibold uppercase tracking-wider mb-1">Total Raised</p>
                <p class="text-3xl font-bold text-white">${{ number_format($totalRaised, 0) }}</p>
                <p class="text-primary-200 dark:text-primary-300 text-xs mt-2">From {{ $donationCount }} donations</p>
            </div>

            <!-- Total Campaigns -->
            <div class="glass-card relative p-6 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-primary-100/10 dark:bg-primary-900/20 rounded-full -translate-y-6 translate-x-6"></div>
                <p class="text-secondary-400 dark:text-secondary-500 text-xs font-semibold uppercase tracking-wider mb-1">Total Campaigns</p>
                <p class="text-3xl font-bold text-primary-900 dark:text-white">{{ $totalCampaigns }}</p>
                <p class="text-secondary-400 dark:text-secondary-500 text-xs mt-2">{{ $activeCampaigns }} currently active</p>
            </div>

            <!-- Active Campaigns -->
            <div class="glass-card relative p-6 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-100/10 dark:bg-emerald-900/20 rounded-full -translate-y-6 translate-x-6"></div>
                <p class="text-secondary-400 dark:text-secondary-500 text-xs font-semibold uppercase tracking-wider mb-1">Active Campaigns</p>
                <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $activeCampaigns }}</p>
                <p class="text-secondary-400 dark:text-secondary-500 text-xs mt-2">Accepting donations now</p>
            </div>

        </div>

        <!-- QUICK LINKS (if admin) -->
        @if(auth()->user()->role === 'admin')
        <div class="mb-8 p-5 glass-card bg-primary-50/70 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-900/40">
            <p class="text-xs font-semibold text-primary-900 dark:text-primary-400 uppercase tracking-wider mb-3">⚙️ Admin Quick Access</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-primary-900 dark:bg-primary-800 text-white text-sm font-medium rounded-xl hover:bg-primary-800 dark:hover:bg-primary-700 transition shadow-sm">
                    🛡️ Admin Portal
                </a>
                <a href="{{ route('admin.donations.index') }}?status=pending"
                    class="flex items-center gap-2 px-4 py-2 bg-amber-600 dark:bg-amber-700 text-white text-sm font-medium rounded-xl hover:bg-amber-700 dark:hover:bg-amber-600 transition shadow-sm">
                    ⏳ Pending Donations
                </a>
                <a href="{{ route('campaigns.create') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-secondary-800 border border-primary-200 dark:border-primary-900/40 text-primary-900 dark:text-primary-400 text-sm font-medium rounded-xl hover:bg-primary-50 dark:hover:bg-secondary-700 transition">
                    ✨ New Campaign
                </a>
            </div>
        </div>
        @endif

        <!-- MAIN CONTENT GRID -->
        <div class="grid lg:grid-cols-3 gap-7">

            <!-- Recent Campaigns -->
            <div class="lg:col-span-2 glass-card overflow-hidden">
                <div class="px-6 py-4 border-b border-secondary-200 dark:border-secondary-700 flex items-center justify-between bg-white/50 dark:bg-secondary-700/50">
                    <h2 class="font-bold text-primary-900 dark:text-white flex items-center gap-2">📊 Recent Campaigns</h2>
                    <a href="{{ route('campaigns.index') }}" class="text-xs text-primary-900 dark:text-primary-400 hover:underline font-medium">View all →</a>
                </div>
                <div class="divide-y divide-secondary-100 dark:divide-secondary-700">
                    @forelse($recentCampaigns as $campaign)
                    @php $progress = $campaign->getProgressPercentage(); @endphp
                    <a href="{{ route('campaigns.show', $campaign) }}"
                        class="block px-6 py-4 hover:bg-primary-50/40 dark:hover:bg-primary-900/20 transition group">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-primary-900 dark:text-secondary-100 group-hover:text-primary-700 dark:group-hover:text-primary-300 transition text-sm">
                                {{ $campaign->title }}
                            </span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $campaign->getStatusBadgeClass() }}">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-xs text-secondary-400 dark:text-secondary-500 mb-1.5">
                            <span class="font-medium">${{ number_format($campaign->current_amount, 0) }}</span>
                            <span>{{ number_format($progress, 0) }}%</span>
                        </div>
                        <div class="h-2 bg-secondary-200 dark:bg-secondary-700 rounded-full overflow-hidden">
                            <div class="h-2 bg-gradient-to-r from-primary-600 to-primary-800 dark:from-primary-700 dark:to-primary-600 rounded-full transition-all"
                                style="width: {{ min($progress, 100) }}%"></div>
                        </div>
                    </a>
                    @empty
                    <div class="px-6 py-10 text-center text-secondary-400 dark:text-secondary-500 text-sm">
                        <div class="text-3xl mb-2">📭</div>
                        No campaigns yet
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Side Panel -->
            <div class="space-y-5">

                <!-- Recent Donations -->
                <div class="glass-card overflow-hidden">
                    <div class="px-5 py-4 border-b border-secondary-200 dark:border-secondary-700 flex items-center justify-between bg-white/50 dark:bg-secondary-700/50">
                        <h2 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2">💝 {{ auth()->user()->role === 'admin' ? 'Recent Donations' : 'My Recent Donations' }}</h2>
                        <a href="{{ route('donations.my') }}" class="text-xs text-primary-900 dark:text-primary-400 hover:underline">Mine →</a>
                    </div>
                    <div class="divide-y divide-secondary-100 dark:divide-secondary-700">
                        @forelse($donations->take(5) as $donation)
                        <div class="px-5 py-3 flex items-center justify-between hover:bg-primary-50/30 dark:hover:bg-primary-900/10 transition">
                            <div>
                                <p class="text-xs font-medium text-secondary-700 dark:text-secondary-200">{{ auth()->user()->role === 'admin' ? ($donation->user->name ?? 'Anonymous Donor') : 'You' }}</p>
                                <p class="text-xs text-secondary-400 dark:text-secondary-500 truncate max-w-32">{{ $donation->campaign->title ?? '—' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-primary-900 dark:text-primary-400">${{ number_format($donation->amount, 0) }}</p>
                                <span class="text-xs font-semibold
                                        @if($donation->status === 'paid') text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30
                                        @elseif($donation->status === 'pending') text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30
                                        @else text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 @endif
                                        px-2 py-0.5 rounded">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="px-5 py-8 text-center text-secondary-400 dark:text-secondary-500 text-sm">
                            <div class="text-3xl mb-2">🔍</div>
                            No donations yet
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="glass-card p-5">
                    <h2 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">⚡ Quick Actions</h2>
                    <div class="space-y-2">
                        <a href="{{ route('campaigns.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary-50 dark:bg-primary-900/20 hover:bg-primary-100 dark:hover:bg-primary-900/40 transition group">
                            <span class="text-xl">🎯</span>
                            <span class="text-sm font-medium text-primary-900 dark:text-primary-400 group-hover:text-primary-800 dark:group-hover:text-primary-300">Browse Campaigns</span>
                        </a>
                        <a href="{{ route('volunteers.create') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl bg-secondary-100 dark:bg-secondary-800 hover:bg-secondary-200 dark:hover:bg-secondary-700 transition group">
                            <span class="text-xl">🙋</span>
                            <span class="text-sm font-medium text-secondary-700 dark:text-secondary-300">Become Volunteer</span>
                        </a>
                        <a href="{{ route('donations.my') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary-50 dark:bg-primary-900/20 hover:bg-primary-100 dark:hover:bg-primary-900/40 transition group">
                            <span class="text-xl">💙</span>
                            <span class="text-sm font-medium text-primary-900 dark:text-primary-400">My Donations</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>