<x-app-layout>
    @section('title', 'Campaigns — CharityHub')

    <!-- HERO SECTION -->
    <div class="relative w-full h-72 md:h-80 overflow-hidden bg-gradient-to-br from-primary-900 via-primary-800 to-primary-900">
        <!-- Background Image -->
        <div class="absolute inset-0 opacity-25"
             style="background-image: url('https://images.unsplash.com/photo-1559027615-cd2628902d4a?w=1200&q=80');
                    background-size: cover;
                    background-position: center;">
        </div>

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-primary-900/50 via-primary-900/70 to-primary-900/90"></div>

        <!-- Hero Content -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white px-4 max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-3 drop-shadow-lg">
                    🎯 Support Causes That Matter
                </h1>
                <p class="text-lg md:text-xl text-gray-100 drop-shadow-md">
                    Browse active campaigns and make a real difference in people's lives
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-primary-900 dark:text-white tracking-tight">All Campaigns</h2>
                <p class="text-secondary-600 dark:text-secondary-400 text-sm mt-1">Find and support causes that matter to you</p>
            </div>
            @can('create', App\Models\Campaign::class)
                <a href="{{ route('campaigns.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-900 to-primary-800 dark:from-primary-800 dark:to-primary-700 text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-xl hover:scale-[1.02] transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    ✨ New Campaign
                </a>
            @endcan
        </div>

        <!-- FILTER BAR -->
        <form method="GET" class="mb-8 flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search campaigns..."
                   class="flex-1 min-w-48 px-4 py-2 rounded-xl border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-800 dark:text-white glass-input text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-600">

            @foreach(['active' => 'Active', 'completed' => 'Completed', 'expired' => 'Expired'] as $s => $label)
                <a href="{{ request()->fullUrlWithQuery(['status' => $s]) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition
                       {{ request('status') === $s
                           ? 'bg-primary-900 dark:bg-primary-800 text-white shadow-md'
                           : 'bg-white dark:bg-secondary-800 text-secondary-700 dark:text-secondary-300 border border-secondary-200 dark:border-secondary-700 hover:border-primary-300 dark:hover:border-primary-700' }}">
                    {{ $label }}
                </a>
            @endforeach

            @if(request('search') || request('status'))
                <a href="{{ route('campaigns.index') }}"
                   class="px-4 py-2 rounded-xl text-sm text-secondary-500 dark:text-secondary-400 bg-white dark:bg-secondary-800 border border-secondary-200 dark:border-secondary-700 hover:text-red-600 dark:hover:text-red-400 transition">
                    ✕ Clear
                </a>
            @endif

            @if(request('search'))
                <button type="submit"
                        class="px-5 py-2 bg-primary-900 dark:bg-primary-800 text-white text-sm rounded-xl hover:bg-primary-800 dark:hover:bg-primary-700 transition font-medium">
                    🔍 Search
                </button>
            @endif
        </form>

        <!-- CAMPAIGN GRID -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7">
            @forelse($campaigns as $campaign)
                @php $progress = $campaign->getProgressPercentage(); @endphp

                <article class="group glass-card hover:shadow-2xl rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 flex flex-col">

                    <!-- IMAGE -->
                    <div class="relative h-44 bg-gradient-to-br from-primary-200 dark:from-primary-900 to-primary-100 dark:to-primary-800 overflow-hidden">
                        @if($campaign->image)
                            <img src="{{ asset('storage/'.$campaign->image) }}"
                                 alt="{{ $campaign->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-16 h-16 text-primary-300 dark:text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <span class="text-xs bg-black/50 dark:bg-primary-900/70 text-white px-2.5 py-1 rounded-full backdrop-blur font-medium">
                                    ⏰ {{ $campaign->deadline->diffForHumans() }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- CONTENT -->
                    <div class="p-5 flex flex-col flex-1">
                        <h2 class="font-bold text-primary-900 dark:text-white text-base leading-snug mb-2 line-clamp-2">
                            {{ $campaign->title }}
                        </h2>
                        <p class="text-secondary-600 dark:text-secondary-400 text-sm leading-relaxed mb-4 line-clamp-3 flex-1">
                            {{ $campaign->description }}
                        </p>

                        <!-- PROGRESS -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center text-xs text-secondary-600 dark:text-secondary-400 mb-1.5">
                                <span class="font-semibold text-primary-900 dark:text-primary-400">${{ number_format($campaign->current_amount, 0) }}</span>
                                <span>{{ number_format($progress, 0) }}% of ${{ number_format($campaign->goal_amount, 0) }}</span>
                            </div>
                            <div class="h-2 bg-secondary-200 dark:bg-secondary-700 rounded-full overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-700
                                    @if($campaign->status === 'completed') bg-gradient-to-r from-emerald-500 to-emerald-600
                                    @elseif($campaign->status === 'expired') bg-gray-500 dark:bg-gray-600
                                    @else bg-gradient-to-r from-primary-600 to-primary-800 dark:from-primary-700 dark:to-primary-600 @endif"
                                     style="width: {{ min($progress, 100) }}%">
                                </div>
                            </div>
                        </div>

                        <!-- ACTIONS -->
                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ route('campaigns.show', $campaign) }}"
                               class="flex-1 text-center py-2 rounded-xl text-sm font-semibold transition-all duration-200
                                   @if($campaign->isAcceptingDonations())
                                       bg-gradient-to-r from-primary-900 to-primary-800 dark:from-primary-800 dark:to-primary-700 text-white hover:shadow-md hover:scale-[1.02]
                                   @else
                                       bg-secondary-200 dark:bg-secondary-700 text-secondary-700 dark:text-secondary-300 hover:bg-secondary-300 dark:hover:bg-secondary-600
                                   @endif">
                                @if($campaign->isAcceptingDonations())
                                    ❤️ Donate Now
                                @else
                                    👁️ View Details
                                @endif
                            </a>

                            @can('update', $campaign)
                                <div class="flex gap-1.5">
                                    <a href="{{ route('campaigns.edit', $campaign) }}"
                                       class="p-2 rounded-lg bg-primary-100 dark:bg-primary-900/40 text-primary-900 dark:text-primary-400 hover:bg-primary-200 dark:hover:bg-primary-900/60 transition"
                                       title="Edit campaign">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @can('delete', $campaign)
                                        <form method="POST" action="{{ route('campaigns.destroy', $campaign) }}"
                                              onsubmit="return confirm('Delete this campaign?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button class="p-2 rounded-lg bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/60 transition"
                                                    title="Delete campaign">
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
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary-100 dark:bg-primary-900/40 mb-6">
                        <svg class="w-10 h-10 text-primary-400 dark:text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-primary-900 dark:text-white mb-2">No campaigns found 📭</h2>
                    <p class="text-secondary-600 dark:text-secondary-400 text-sm">
                        @if(request('status') || request('search'))
                            Try adjusting your filters to find what you're looking for.
                        @else
                            No campaigns have been created yet. Check back soon!
                        @endif
                    </p>
                    @if(request('status') || request('search'))
                        <a href="{{ route('campaigns.index') }}" class="inline-block mt-4 text-sm text-primary-900 dark:text-primary-400 hover:underline font-medium">
                            ← Clear filters
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