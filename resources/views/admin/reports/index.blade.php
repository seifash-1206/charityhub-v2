<x-admin-layout>
    @section('title', 'Reports')
    @section('page-title', 'Reports & Analytics')
    @section('page-subtitle', 'Platform insights and performance data')

    <!-- TOP STATS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-5 shadow-lg">
            <p class="text-indigo-200 text-xs mb-2 font-semibold uppercase">Total Raised</p>
            <p class="text-2xl font-bold text-white">${{ number_format($totalRaised, 2) }}</p>
        </div>
        <div class="bg-gray-800 border border-white/5 rounded-2xl p-5 shadow-lg">
            <p class="text-gray-400 text-xs mb-2 uppercase">Approved Donations</p>
            <p class="text-2xl font-bold text-white">{{ $paidDonations }}</p>
        </div>
        <div class="bg-gray-800 border border-white/5 rounded-2xl p-5 shadow-lg">
            <p class="text-gray-400 text-xs mb-2 uppercase">Avg Donation</p>
            <p class="text-2xl font-bold text-white">${{ number_format($avgDonation, 2) }}</p>
        </div>
        <div class="bg-gray-800 border border-white/5 rounded-2xl p-5 shadow-lg">
            <p class="text-gray-400 text-xs mb-2 uppercase">Total Volunteer Hours</p>
            <p class="text-2xl font-bold text-white">{{ number_format($totalHoursLogged, 0) }}h</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">

        <!-- Campaign Status Breakdown -->
        <div class="bg-gray-800 border border-white/5 rounded-2xl p-6 shadow-lg">
            <h3 class="text-sm font-semibold text-white mb-5">Campaign Status Breakdown</h3>
            @php $totalC = array_sum($campaignStats); @endphp
            @foreach([
                'active' => ['color' => 'bg-emerald-500', 'label' => 'Active'],
                'completed' => ['color' => 'bg-blue-500', 'label' => 'Completed'],
                'expired' => ['color' => 'bg-red-500', 'label' => 'Expired'],
                'draft' => ['color' => 'bg-gray-500', 'label' => 'Draft'],
            ] as $status => $meta)
                @php
                    $count = $campaignStats[$status] ?? 0;
                    $pct = $totalC > 0 ? ($count / $totalC) * 100 : 0;
                @endphp
                <div class="mb-4">
                    <div class="flex justify-between text-xs text-gray-400 mb-1.5">
                        <span>{{ $meta['label'] }}</span>
                        <span>{{ $count }} ({{ number_format($pct, 0) }}%)</span>
                    </div>
                    <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-2 {{ $meta['color'] }} rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Top Donors -->
        <div class="bg-gray-800 border border-white/5 rounded-2xl overflow-hidden shadow-lg">
            <div class="px-6 py-4 border-b border-white/5">
                <h3 class="text-sm font-semibold text-white">Top Donors</h3>
            </div>
            <div class="divide-y divide-white/5">
                @forelse($topDonors as $index => $donorStat)
                    <div class="px-6 py-3 flex items-center gap-3">
                        <span class="text-xs font-bold text-gray-600 w-5">{{ $index + 1 }}</span>
                        <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($donorStat->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-200 truncate">{{ $donorStat->user->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $donorStat->donation_count }} donations</p>
                        </div>
                        <p class="text-sm font-bold text-white">${{ number_format($donorStat->total_donated, 0) }}</p>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 text-sm">No donor data</div>
                @endforelse
            </div>
        </div>

        <!-- Top Campaigns -->
        <div class="bg-gray-800 border border-white/5 rounded-2xl overflow-hidden shadow-lg">
            <div class="px-6 py-4 border-b border-white/5">
                <h3 class="text-sm font-semibold text-white">Top Campaigns by Raised</h3>
            </div>
            <div class="divide-y divide-white/5">
                @forelse($topCampaigns as $index => $campaign)
                    <div class="px-6 py-3">
                        <div class="flex items-center justify-between mb-1.5">
                            <p class="text-sm text-gray-200 truncate mr-3">{{ $campaign->title }}</p>
                            <p class="text-sm font-bold text-white flex-shrink-0">
                                ${{ number_format($campaign->raised ?? 0, 0) }}
                            </p>
                        </div>
                        @php $pct = $campaign->goal_amount > 0 ? min(($campaign->raised / $campaign->goal_amount) * 100, 100) : 0; @endphp
                        <div class="h-1.5 bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-1.5 bg-indigo-500 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-0.5">{{ number_format($pct, 0) }}% funded</p>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 text-sm">No campaign data</div>
                @endforelse
            </div>
        </div>

        <!-- Monthly Donation Trend -->
        <div class="bg-gray-800 border border-white/5 rounded-2xl p-6 shadow-lg">
            <h3 class="text-sm font-semibold text-white mb-5">Monthly Donations (Last 12 Months)</h3>
            @if($monthlyDonations->count() > 0)
                @php $maxAmount = $monthlyDonations->max('total') ?: 1; @endphp
                <div class="space-y-2">
                    @foreach($monthlyDonations as $month)
                        @php $width = ($month->total / $maxAmount) * 100; @endphp
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400 w-16 flex-shrink-0">{{ $month->month }}</span>
                            <div class="flex-1 h-5 bg-gray-700 rounded overflow-hidden">
                                <div class="h-5 bg-gradient-to-r from-indigo-600 to-purple-600 rounded" style="width: {{ $width }}%"></div>
                            </div>
                            <span class="text-xs text-gray-300 w-20 text-right flex-shrink-0">${{ number_format($month->total, 0) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm text-center py-8">No donation data yet</p>
            @endif
        </div>

    </div>

</x-admin-layout>
