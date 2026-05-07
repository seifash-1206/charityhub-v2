<x-admin-layout>
    @section('title', 'Volunteers')
    @section('page-title', 'Volunteer Management')
    @section('page-subtitle', 'Manage all registered volunteers')

    <!-- STATS -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-7">
        <div class="bg-gray-800 border border-white/5 rounded-xl p-4">
            <p class="text-gray-500 text-xs mb-1">Total</p>
            <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-gray-800 border border-emerald-500/20 rounded-xl p-4">
            <p class="text-emerald-400 text-xs mb-1">Active</p>
            <p class="text-2xl font-bold text-emerald-300">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-gray-800 border border-amber-500/20 rounded-xl p-4">
            <p class="text-amber-400 text-xs mb-1">Pending</p>
            <p class="text-2xl font-bold text-amber-300">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-gray-800 border border-white/5 rounded-xl p-4">
            <p class="text-gray-400 text-xs mb-1">Inactive</p>
            <p class="text-2xl font-bold text-gray-400">{{ $stats['inactive'] }}</p>
        </div>
    </div>

    <!-- FILTERS -->
    <form method="GET" class="bg-gray-800 border border-white/5 rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-44">
            <label class="block text-xs text-gray-400 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Name, email, phone..."
                   class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Status</label>
            <select name="status" class="bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">All</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Campaign</label>
            <select name="campaign_id" class="bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">All Campaigns</option>
                @foreach($campaigns as $campaign)
                    <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                        {{ Str::limit($campaign->title, 30) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Availability</label>
            <select name="availability" class="bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">Any</option>
                <option value="weekdays" {{ request('availability') === 'weekdays' ? 'selected' : '' }}>Weekdays</option>
                <option value="weekends" {{ request('availability') === 'weekends' ? 'selected' : '' }}>Weekends</option>
                <option value="both" {{ request('availability') === 'both' ? 'selected' : '' }}>Both</option>
                <option value="flexible" {{ request('availability') === 'flexible' ? 'selected' : '' }}>Flexible</option>
            </select>
        </div>
        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition">Filter</button>
        <a href="{{ route('admin.volunteers.index') }}" class="px-5 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm rounded-lg transition">Reset</a>
    </form>

    <!-- TABLE -->
    <div class="bg-gray-800 border border-white/5 rounded-2xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-900/60">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Volunteer</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Campaign</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Availability</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Hours</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($volunteers as $volunteer)
                        <tr class="hover:bg-white/2 transition">
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-gray-200">{{ $volunteer->name }}</p>
                                <p class="text-xs text-gray-500">{{ $volunteer->email }}</p>
                                @if($volunteer->phone)
                                    <p class="text-xs text-gray-600">{{ $volunteer->phone }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-gray-300 text-xs">
                                {{ $volunteer->campaign->title ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-400">
                                {{ $volunteer->getAvailabilityLabel() }}
                            </td>
                            <td class="px-5 py-3.5 text-sm font-semibold text-white">
                                {{ number_format($volunteer->hours_logged, 1) }}h
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $volunteer->getStatusBadgeClass() }}">
                                    {{ ucfirst($volunteer->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.volunteers.show', $volunteer) }}"
                                       class="p-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.volunteers.edit', $volunteer) }}"
                                       class="p-1.5 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 hover:text-indigo-300 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <!-- Quick status toggle -->
                                    <form method="POST" action="{{ route('admin.volunteers.status', $volunteer) }}">
                                        @csrf
                                        <input type="hidden" name="status"
                                               value="{{ $volunteer->status === 'active' ? 'inactive' : 'active' }}">
                                        <button class="p-1.5 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 hover:text-amber-300 transition" title="Toggle Status">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                            </svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.volunteers.destroy', $volunteer) }}"
                                          onsubmit="return confirm('Delete {{ addslashes($volunteer->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button class="p-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-500">No volunteers found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-white/5 bg-gray-900/30">
            {{ $volunteers->links() }}
        </div>
    </div>

</x-admin-layout>
