<x-app-layout>
    @section('title', 'Campaigns — CharityHub')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Campaigns</h1>
                <p class="text-gray-500 text-sm mt-1">Support causes that matter — browse active campaigns below.</p>
            </div>
            @can('create', App\Models\Campaign::class)
                <a href="{{ route('campaigns.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-800 text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-xl hover:scale-[1.02] transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Campaign
                </a>
            @endcan
        </div>

        <!-- FILTER BAR -->
        <form method="GET" class="mb-8 flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search campaigns..."
                   class="flex-1 min-w-48 px-4 py-2 rounded-xl border border-gray-200 bg-white/80 backdrop-blur text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

            @foreach(['active' => 'Active', 'completed' => 'Completed', 'expired' => 'Expired'] as $s => $label)
                <a href="{{ request()->fullUrlWithQuery(['status' => $s]) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition
                       {{ request('status') === $s
                           ? 'bg-blue-600 text-white shadow-md'
                           : 'bg-white/80 text-gray-600 border border-gray-200 hover:border-blue-300' }}">
                    {{ $label }}
                </a>
            @endforeach

            @if(request('search') || request('status'))
                <a href="{{ route('campaigns.index') }}"
                   class="px-4 py-2 rounded-xl text-sm text-gray-500 bg-white/80 border border-gray-200 hover:text-red-500 transition">
                    Clear
                </a>
            @endif

            @if(request('search'))
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm rounded-xl hover:bg-blue-700 transition">
                    Search
                </button>
            @endif
        </form>

        <!-- CAMPAIGN GRID -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7">
            @forelse($campaigns as $campaign)
                @php $progress = $campaign->getProgressPercentage(); @endphp

                <article class="group bg-white/60 backdrop-blur-xl border border-white/60 shadow-lg hover:shadow-2xl rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 flex flex-col">

                    <!-- IMAGE -->
                    <div class="relative h-44 bg-gradient-to-br from-blue-100 to-indigo-100 overflow-hidden">
                        @if($campaign->image)
                            <img src="{{ asset('storage/'.$campaign->image) }}"
                                 alt="{{ $campaign->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        <!-- STATUS BADGE -->
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full {{ $campaign->getStatusBadgeClass() }} backdrop-blur shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full {{ $campaign->getStatusDotClass() }}"></span>
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </div>

                        <!-- DEADLINE BADGE -->
                        @if($campaign->deadline && $campaign->status === 'active')
                            <div class="absolute bottom-3 left-3">
                                <span class="text-xs bg-black/50 text-white px-2.5 py-1 rounded-full backdrop-blur">
                                    {{ $campaign->deadline->diffForHumans() }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- CONTENT -->
                    <div class="p-5 flex flex-col flex-1">
                        <h2 class="font-bold text-gray-900 text-base leading-snug mb-2 line-clamp-2">
                            {{ $campaign->title }}
                        </h2>
                        <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-3 flex-1">
                            {{ $campaign->description }}
                        </p>

                        <!-- PROGRESS -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center text-xs text-gray-500 mb-1.5">
                                <span class="font-semibold text-gray-800">${{ number_format($campaign->current_amount, 0) }}</span>
                                <span>{{ number_format($progress, 0) }}% of ${{ number_format($campaign->goal_amount, 0) }}</span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-700
                                    @if($campaign->status === 'completed') bg-gradient-to-r from-blue-500 to-blue-600
                                    @elseif($campaign->status === 'expired') bg-gray-400
                                    @else bg-gradient-to-r from-blue-500 to-indigo-600 @endif"
                                     style="width: {{ min($progress, 100) }}%">
                                </div>
                            </div>
                        </div>

                        <!-- ACTIONS -->
                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ route('campaigns.show', $campaign) }}"
                               class="flex-1 text-center py-2 rounded-xl text-sm font-semibold
                                   @if($campaign->isAcceptingDonations())
                                       bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:shadow-md hover:scale-[1.02]
                                   @else
                                       bg-gray-100 text-gray-600 hover:bg-gray-200
                                   @endif transition-all duration-200">
                                @if($campaign->isAcceptingDonations())
                                    Donate Now
                                @else
                                    View Details
                                @endif
                            </a>

                            @can('update', $campaign)
                                <div class="flex gap-1.5">
                                    <a href="{{ route('campaigns.edit', $campaign) }}"
                                       class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @can('delete', $campaign)
                                        <form method="POST" action="{{ route('campaigns.destroy', $campaign) }}"
                                              onsubmit="return confirm('Delete this campaign?')">
                                            @csrf @method('DELETE')
                                            <button class="p-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            @endcan
                        </div>
                    </div>
                </article>

            @empty
                <!-- EMPTY STATE -->
                <div class="col-span-3 py-20 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-6">
                        <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-700 mb-2">No campaigns found</h2>
                    <p class="text-gray-400 text-sm">
                        @if(request('status') || request('search'))
                            Try adjusting your filters.
                        @else
                            No campaigns have been created yet.
                        @endif
                    </p>
                    @if(request('status') || request('search'))
                        <a href="{{ route('campaigns.index') }}" class="inline-block mt-4 text-sm text-blue-600 hover:underline">
                            Clear filters
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        @if($campaigns->hasPages())
            <div class="mt-10">
                {{ $campaigns->links() }}
            </div>
        @endif

    </div>

</x-app-layout>