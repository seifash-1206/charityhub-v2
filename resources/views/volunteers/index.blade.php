<x-app-layout>
    @section('title', 'Volunteers — CharityHub')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Volunteers</h1>
                <p class="text-gray-500 text-sm mt-1">Meet the people making a difference. <span class="font-semibold text-blue-600">{{ $stats['total'] }} registered</span>, {{ $stats['active'] }} active.</p>
            </div>
            <a href="{{ route('volunteers.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-800 text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-xl hover:scale-[1.02] transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Become a Volunteer
            </a>
        </div>

        <!-- FILTERS -->
        <form method="GET" class="mb-8 flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by name or email..."
                   class="flex-1 min-w-48 px-4 py-2 rounded-xl border border-gray-200 bg-white/80 backdrop-blur text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="campaign_id"
                    class="px-4 py-2 rounded-xl border border-gray-200 bg-white/80 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Campaigns</option>
                @foreach($campaigns as $c)
                    <option value="{{ $c->id }}" {{ request('campaign_id') == $c->id ? 'selected' : '' }}>
                        {{ Str::limit($c->title, 30) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white text-sm rounded-xl hover:bg-blue-700 transition">
                Search
            </button>
            @if(request()->anyFilled(['search', 'campaign_id']))
                <a href="{{ route('volunteers.index') }}" class="px-4 py-2 rounded-xl text-sm text-gray-500 bg-white/80 border border-gray-200 hover:text-red-500 transition">
                    Clear
                </a>
            @endif
        </form>

        <!-- VOLUNTEER GRID -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($volunteers as $volunteer)
                <div class="group bg-white/60 backdrop-blur-xl border border-white/60 shadow-lg hover:shadow-xl rounded-2xl p-6 transition-all duration-300 hover:-translate-y-0.5">

                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-lg font-bold flex-shrink-0">
                                {{ strtoupper(substr($volunteer->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 leading-tight">{{ $volunteer->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $volunteer->email }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-full {{ $volunteer->getStatusBadgeClass() }}">
                            {{ ucfirst($volunteer->status) }}
                        </span>
                    </div>

                    <!-- Details -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span class="truncate">{{ $volunteer->campaign->title ?? 'No Campaign' }}</span>
                        </div>

                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $volunteer->getAvailabilityLabel() }}</span>
                        </div>

                        @if($volunteer->phone)
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span>{{ $volunteer->phone }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Hours badge -->
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-semibold text-blue-700">{{ number_format($volunteer->hours_logged, 0) }}h</span>
                            <span class="text-xs text-gray-400">logged</span>
                        </div>
                        @if($volunteer->skills)
                            <p class="text-xs text-gray-400 truncate max-w-32" title="{{ $volunteer->skills }}">
                                {{ Str::limit($volunteer->skills, 25) }}
                            </p>
                        @endif
                    </div>

                    <!-- User owns this record -->
                    @auth
                        @if($volunteer->user_id === auth()->id())
                            <div class="flex gap-2 mt-3 pt-3 border-t border-gray-100">
                                <a href="{{ route('volunteers.edit', $volunteer) }}"
                                   class="flex-1 text-center text-xs py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition font-medium">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('volunteers.destroy', $volunteer) }}"
                                      onsubmit="return confirm('Remove your volunteer registration?')">
                                    @csrf @method('DELETE')
                                    <button class="flex-1 text-xs py-1.5 px-3 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition font-medium">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth

                </div>
            @empty
                <div class="col-span-3 py-20 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-6">
                        <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-700 mb-2">No volunteers yet</h2>
                    <p class="text-gray-400 text-sm mb-6">Be the first to make a difference!</p>
                    <a href="{{ route('volunteers.create') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-800 text-white text-sm font-semibold rounded-xl hover:shadow-lg transition">
                        Register as Volunteer
                    </a>
                </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        @if($volunteers->hasPages())
            <div class="mt-10">
                {{ $volunteers->links() }}
            </div>
        @endif

    </div>

</x-app-layout>