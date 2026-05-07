<x-admin-layout>
    @section('title', 'Campaigns')
    @section('page-title', 'Campaign Management')
    @section('page-subtitle', 'Manage all campaigns — statuses, goals, deadlines')

    <!-- STATS -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-7">
        @foreach([
            ['label' => 'Total', 'count' => $stats['total'], 'color' => 'text-white'],
            ['label' => 'Active', 'count' => $stats['active'], 'color' => 'text-emerald-400'],
            ['label' => 'Completed', 'count' => $stats['completed'], 'color' => 'text-blue-400'],
            ['label' => 'Expired', 'count' => $stats['expired'], 'color' => 'text-red-400'],
        ] as $stat)
            <div class="bg-gray-800 border border-white/5 rounded-xl p-4">
                <p class="text-gray-500 text-xs mb-1">{{ $stat['label'] }}</p>
                <p class="text-2xl font-bold {{ $stat['color'] }}">{{ $stat['count'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- FILTERS -->
    <form method="GET" class="bg-gray-800 border border-white/5 rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs text-gray-400 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Title or description..."
                   class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Status</label>
            <select name="status" class="bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>
        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition">
            Filter
        </button>
        <a href="{{ route('admin.campaigns.index') }}" class="px-5 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm rounded-lg transition">
            Reset
        </a>
    </form>

    <!-- TABLE -->
    <div class="bg-gray-800 border border-white/5 rounded-2xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-900/60">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Campaign</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Progress</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Deadline</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($campaigns as $campaign)
                        @php
                            $percent = $campaign->goal_amount > 0
                                ? min(($campaign->current_amount / $campaign->goal_amount) * 100, 100)
                                : 0;
                        @endphp
                        <tr class="hover:bg-white/2 transition">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($campaign->image)
                                        <img src="{{ asset('storage/'.$campaign->image) }}"
                                             class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-700 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-200">{{ $campaign->title }}</p>
                                        <p class="text-xs text-gray-500">by {{ $campaign->user->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="w-32">
                                    <div class="flex justify-between text-xs text-gray-400 mb-1">
                                        <span>${{ number_format($campaign->current_amount, 0) }}</span>
                                        <span>{{ number_format($percent, 0) }}%</span>
                                    </div>
                                    <div class="h-1.5 bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-1.5 bg-indigo-500 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">Goal: ${{ number_format($campaign->goal_amount, 0) }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-400">
                                {{ $campaign->deadline ? $campaign->deadline->format('M d, Y') : '—' }}
                                @if($campaign->deadline && $campaign->deadline->isFuture())
                                    <p class="text-gray-600">{{ $campaign->deadline->diffForHumans() }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                                    @if($campaign->status === 'active') bg-emerald-900/50 text-emerald-400
                                    @elseif($campaign->status === 'completed') bg-blue-900/50 text-blue-400
                                    @elseif($campaign->status === 'expired') bg-red-900/50 text-red-400
                                    @else bg-gray-700 text-gray-400 @endif">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.campaigns.show', $campaign) }}"
                                       class="p-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition" title="View">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.campaigns.edit', $campaign) }}"
                                       class="p-1.5 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 hover:text-indigo-300 transition" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.campaigns.destroy', $campaign) }}"
                                          onsubmit="return confirm('Delete this campaign?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 transition" title="Delete">
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
                            <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                                No campaigns found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 border-t border-white/5 bg-gray-900/30">
            {{ $campaigns->links() }}
        </div>
    </div>

</x-admin-layout>
