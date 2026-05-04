<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-white via-blue-50 to-blue-100 p-8">

    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Dashboard 👋
        </h1>
        <p class="text-gray-500">
            Welcome back, {{ auth()->user()->name }}
        </p>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- TOTAL DONATIONS -->
        <div class="backdrop-blur-lg bg-white/60 border border-white/40 shadow-lg rounded-2xl p-6">
            <h2 class="text-gray-500 text-sm">Total Donations</h2>
            <p class="text-2xl font-bold text-gray-800 mt-2">
                ${{ number_format($totalRaised ?? 0, 2) }}
            </p>
        </div>

        <!-- CAMPAIGNS -->
        <div class="backdrop-blur-lg bg-white/60 border border-white/40 shadow-lg rounded-2xl p-6">
            <h2 class="text-gray-500 text-sm">Campaigns</h2>
            <p class="text-2xl font-bold text-gray-800 mt-2">
                {{ $totalCampaigns ?? 0 }}
            </p>
        </div>

        <!-- ACTIVE CAMPAIGNS -->
        <div class="backdrop-blur-lg bg-white/60 border border-white/40 shadow-lg rounded-2xl p-6">
            <h2 class="text-gray-500 text-sm">Active Campaigns</h2>
            <p class="text-2xl font-bold text-red-500 mt-2">
                {{ $activeCampaigns ?? 0 }}
            </p>
        </div>

    </div>

    <!-- MAIN -->
    <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- RECENT CAMPAIGNS -->
        <div class="backdrop-blur-lg bg-white/60 border border-white/40 shadow-lg rounded-2xl p-6">

            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Recent Campaigns
            </h2>

            <div class="space-y-4">

                @forelse($recentCampaigns as $campaign)

                    @php
                        $progress = $campaign->goal_amount > 0 
                            ? ($campaign->current_amount / $campaign->goal_amount) * 100 
                            : 0;
                    @endphp

                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-gray-700">
                                {{ $campaign->title }}
                            </span>
                            <span class="text-gray-500">
                                ${{ number_format($campaign->current_amount, 2) }} 
                                / 
                                ${{ number_format($campaign->goal_amount, 2) }}
                            </span>
                        </div>

                        <!-- PROGRESS BAR -->
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-500 h-2 rounded-full transition-all duration-500"
                                 style="width: {{ min($progress, 100) }}%">
                            </div>
                        </div>
                    </div>

                @empty
                    <p class="text-gray-500 text-sm">
                        No campaigns yet
                    </p>
                @endforelse

            </div>

        </div>

        <!-- ACTIVITY PLACEHOLDER -->
        <div class="backdrop-blur-lg bg-white/40 border border-white/30 shadow rounded-2xl p-6 flex items-center justify-center">
            <p class="text-gray-400 text-sm">
                Activity system coming soon...
            </p>
        </div>

    </div>

</div>

</x-app-layout>