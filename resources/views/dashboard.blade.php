<x-app-layout>
    @section('title', 'Dashboard — CharityHub')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- HERO HEADER -->
        <div class="mb-8">
            <p class="text-gray-400 text-sm font-medium">Welcome back,</p>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ auth()->user()->name }} 👋</h1>
            @if(auth()->user()->role === 'admin')
                <div class="mt-2 inline-flex items-center gap-2 text-xs px-3 py-1.5 rounded-full bg-indigo-100 text-indigo-700 font-semibold">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Admin Account
                </div>
            @endif
        </div>

        <!-- STATS ROW -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-10">

            <!-- Total Raised -->
            <div class="relative bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 shadow-lg shadow-blue-200/50 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-8 translate-x-8"></div>
                <p class="text-blue-200 text-xs font-semibold uppercase tracking-wider mb-1">Total Raised</p>
                <p class="text-3xl font-bold text-white">${{ number_format($totalRaised, 0) }}</p>
                <p class="text-blue-200 text-xs mt-2">From {{ $donations->count() }} donations</p>
            </div>

            <!-- Total Campaigns -->
            <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl p-6 shadow-lg">
                <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Total Campaigns</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalCampaigns }}</p>
                <p class="text-gray-400 text-xs mt-2">{{ $activeCampaigns }} currently active</p>
            </div>

            <!-- Active Campaigns -->
            <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl p-6 shadow-lg">
                <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Active Campaigns</p>
                <p class="text-3xl font-bold text-emerald-600">{{ $activeCampaigns }}</p>
                <p class="text-gray-400 text-xs mt-2">Accepting donations now</p>
            </div>

        </div>

        <!-- QUICK LINKS (if admin) -->
        @if(auth()->user()->role === 'admin')
            <div class="mb-8 p-5 bg-indigo-50/70 backdrop-blur border border-indigo-100 rounded-2xl shadow-sm">
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-3">Admin Quick Access</p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        🛡️ Admin Portal
                    </a>
                    <a href="{{ route('admin.donations.index') }}?status=pending"
                       class="flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-xl hover:bg-amber-600 transition shadow-sm">
                        ⏳ Pending Donations
                    </a>
                    <a href="{{ route('campaigns.create') }}"
                       class="flex items-center gap-2 px-4 py-2 bg-white border border-indigo-200 text-indigo-700 text-sm font-medium rounded-xl hover:bg-indigo-50 transition">
                        + New Campaign
                    </a>
                </div>
            </div>
        @endif

        <!-- MAIN CONTENT GRID -->
        <div class="grid lg:grid-cols-3 gap-7">

            <!-- Recent Campaigns -->
            <div class="lg:col-span-2 bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-bold text-gray-800">Recent Campaigns</h2>
                    <a href="{{ route('campaigns.index') }}" class="text-xs text-blue-600 hover:underline font-medium">View all →</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentCampaigns as $campaign)
                        @php $progress = $campaign->getProgressPercentage(); @endphp
                        <a href="{{ route('campaigns.show', $campaign) }}"
                           class="block px-6 py-4 hover:bg-blue-50/40 transition group">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-800 group-hover:text-blue-700 transition text-sm">
                                    {{ $campaign->title }}
                                </span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $campaign->getStatusBadgeClass() }}">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-xs text-gray-400 mb-1.5">
                                <span>${{ number_format($campaign->current_amount, 0) }}</span>
                                <span>{{ number_format($progress, 0) }}%</span>
                            </div>
                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-1.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all"
                                     style="width: {{ min($progress, 100) }}%"></div>
                            </div>
                        </a>
                    @empty
                        <div class="px-6 py-10 text-center text-gray-400 text-sm">No campaigns yet</div>
                    @endforelse
                </div>
            </div>

            <!-- Side Panel -->
            <div class="space-y-5">

                <!-- Recent Donations -->
                <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="font-bold text-gray-800 text-sm">Recent Donations</h2>
                        <a href="{{ route('donations.my') }}" class="text-xs text-blue-600 hover:underline">Mine →</a>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse($donations->take(5) as $donation)
                            <div class="px-5 py-3 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-medium text-gray-700">{{ $donation->user->name ?? '—' }}</p>
                                    <p class="text-xs text-gray-400 truncate max-w-32">{{ $donation->campaign->title ?? '—' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-blue-700">${{ number_format($donation->amount, 0) }}</p>
                                    <span class="text-xs
                                        @if($donation->status === 'paid') text-emerald-600
                                        @elseif($donation->status === 'pending') text-amber-600
                                        @else text-red-500 @endif">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-8 text-center text-gray-400 text-sm">No donations yet</div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl p-5 shadow-lg">
                    <h2 class="font-bold text-gray-800 text-sm mb-4">Quick Actions</h2>
                    <div class="space-y-2">
                        <a href="{{ route('campaigns.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-50 hover:bg-blue-100 transition group">
                            <span class="text-lg">🎯</span>
                            <span class="text-sm font-medium text-blue-800 group-hover:text-blue-900">Browse Campaigns</span>
                        </a>
                        <a href="{{ route('donations.track') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition group">
                            <span class="text-lg">🔍</span>
                            <span class="text-sm font-medium text-gray-700">Track a Donation</span>
                        </a>
                        <a href="{{ route('volunteers.create') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition group">
                            <span class="text-lg">🙋</span>
                            <span class="text-sm font-medium text-gray-700">Volunteer</span>
                        </a>
                        <a href="{{ route('donations.my') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition group">
                            <span class="text-lg">💙</span>
                            <span class="text-sm font-medium text-gray-700">My Donations</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>