<x-admin-layout>
    @section('title', 'Dashboard')
    @section('page-title', 'Dashboard')
    @section('page-subtitle', 'Platform overview and key metrics')

    <!-- ═══════════════════════════════════════════ -->
    <!-- STATS GRID -->
    <!-- ═══════════════════════════════════════════ -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <!-- Total Raised -->
        <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-2xl p-5 shadow-lg shadow-primary-900/30">
            <div class="flex items-center justify-between mb-3">
                <p class="text-primary-200 text-xs font-semibold uppercase tracking-wider">Total Raised</p>
                <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">${{ number_format($totalRaised, 2) }}</p>
            <p class="text-indigo-300 text-xs mt-1">From approved donations</p>
        </div>

        <!-- Campaigns -->
        <div class="bg-gray-800 border border-white/5 rounded-2xl p-5 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Campaigns</p>
                <div class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $totalCampaigns }}</p>
            <p class="text-gray-500 text-xs mt-1">{{ $activeCampaigns }} active</p>
        </div>

        <!-- Pending Donations -->
        <div class="bg-gray-800 border border-amber-500/20 rounded-2xl p-5 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <p class="text-amber-400 text-xs font-semibold uppercase tracking-wider">Pending</p>
                <div class="w-8 h-8 bg-amber-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $pendingDonations }}</p>
            <p class="text-gray-500 text-xs mt-1">Awaiting approval</p>
        </div>

        <!-- Volunteers -->
        <div class="bg-gray-800 border border-white/5 rounded-2xl p-5 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Volunteers</p>
                <div class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $totalVolunteers }}</p>
            <p class="text-gray-500 text-xs mt-1">{{ $totalUsers }} users</p>
        </div>

    </div>

    <!-- ═══════════════════════════════════════════ -->
    <!-- MAIN CONTENT GRID -->
    <!-- ═══════════════════════════════════════════ -->
    <div class="grid lg:grid-cols-3 gap-6">

        <!-- LEFT: Recent Donations -->
        <div class="lg:col-span-2 bg-gray-800 border border-white/5 rounded-2xl overflow-hidden shadow-lg">

            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-white">Recent Donations</h2>
                <a href="{{ route('admin.donations.index') }}"
                    class="text-xs text-primary-400 hover:text-primary-300 transition">
                    View all →
                </a>
            </div>

            <div class="divide-y divide-white/5">
                @forelse($recentDonations as $donation)
                <div class="px-6 py-3 flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-primary-600/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-primary-300">
                            {{ strtoupper(substr($donation->user->name ?? 'U', 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-200 truncate">
                            {{ $donation->user->name ?? 'Unknown' }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ $donation->campaign->title ?? '-' }}
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-semibold text-white">${{ number_format($donation->amount, 2) }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full
                                @if($donation->status === 'paid') bg-emerald-900/50 text-emerald-400
                                @elseif($donation->status === 'pending') bg-amber-900/50 text-amber-400
                                @else bg-red-900/50 text-red-400 @endif">
                            {{ ucfirst($donation->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-6 py-10 text-center">
                    <p class="text-gray-500 text-sm">No donations yet</p>
                </div>
                @endforelse
            </div>

        </div>

        <!-- RIGHT: Top Campaigns + Quick Actions -->
        <div class="space-y-5">

            <!-- Quick Actions -->
            <div class="bg-gray-800 border border-white/5 rounded-2xl p-5 shadow-lg">
                <h2 class="text-sm font-semibold text-white mb-4">Quick Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('admin.donations.index') }}?status=pending"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20 hover:bg-amber-500/15 transition group">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse flex-shrink-0"></span>
                        <span class="text-sm text-amber-200 group-hover:text-amber-100">
                            {{ $pendingDonations }} Pending Donations
                        </span>
                    </a>
                    <a href="{{ route('admin.campaigns.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/8 transition">
                        <span class="text-sm text-gray-300">Manage Campaigns</span>
                    </a>
                    <a href="{{ route('admin.volunteers.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/8 transition">
                        <span class="text-sm text-gray-300">Manage Volunteers</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/8 transition">
                        <span class="text-sm text-gray-300">View Reports</span>
                    </a>
                </div>
            </div>

            <!-- Top Campaigns -->
            <div class="bg-gray-800 border border-white/5 rounded-2xl overflow-hidden shadow-lg">
                <div class="px-5 py-4 border-b border-white/5">
                    <h2 class="text-sm font-semibold text-white">Top Campaigns</h2>
                </div>
                <div class="divide-y divide-white/5">
                    @forelse($topCampaigns as $campaign)
                    @php
                    $percent = $campaign->goal_amount > 0
                    ? min(($campaign->paid_total / $campaign->goal_amount) * 100, 100)
                    : 0;
                    @endphp
                    <div class="px-5 py-3">
                        <div class="flex items-center justify-between mb-1.5">
                            <p class="text-xs font-medium text-gray-300 truncate mr-2">{{ $campaign->title }}</p>
                            <p class="text-xs text-primary-400 font-semibold flex-shrink-0">
                                ${{ number_format($campaign->paid_total ?? 0, 0) }}
                            </p>
                        </div>
                        <div class="h-1.5 bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-1.5 bg-gradient-to-r from-primary-600 to-primary-500 rounded-full"
                                style="width: {{ $percent }}%"></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">{{ number_format($percent, 0) }}% of ${{ number_format($campaign->goal_amount, 0) }}</p>
                    </div>
                    @empty
                    <div class="px-5 py-6 text-center text-gray-500 text-sm">No campaign data</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</x-admin-layout>