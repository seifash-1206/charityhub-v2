<x-admin-layout>
    @section('title', 'Donations')
    @section('page-title', 'Donation Management')
    @section('page-subtitle', 'Review, approve, and track all donations')

    <!-- STATS -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-7">
        <div class="bg-gray-800 border border-white/5 rounded-xl p-4">
            <p class="text-gray-500 text-xs mb-1">Total Raised</p>
            <p class="text-xl font-bold text-white">${{ number_format($stats['total_paid'], 2) }}</p>
        </div>
        <div class="bg-gray-800 border border-white/5 rounded-xl p-4">
            <p class="text-gray-500 text-xs mb-1">All Donations</p>
            <p class="text-xl font-bold text-gray-200">{{ $stats['total_count'] }}</p>
        </div>
        <div class="bg-gray-800 border border-amber-500/20 rounded-xl p-4">
            <p class="text-amber-400 text-xs mb-1">Pending</p>
            <p class="text-xl font-bold text-amber-300">{{ $stats['total_pending'] }}</p>
        </div>
        <div class="bg-gray-800 border border-emerald-500/20 rounded-xl p-4">
            <p class="text-emerald-400 text-xs mb-1">Approved</p>
            <p class="text-xl font-bold text-emerald-300">{{ $stats['paid_count'] }}</p>
        </div>
    </div>

    <!-- FILTERS -->
    <form method="GET" class="bg-gray-800 border border-white/5 rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-44">
            <label class="block text-xs text-gray-400 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Donor, email, tracking ID..."
                class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm placeholder-gray-600 focus:outline-none focus:border-primary-500">
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Status</label>
            <select name="status" class="bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
                <option value="">All</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Approved</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Campaign</label>
            <select name="campaign_id" class="bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
                <option value="">All Campaigns</option>
                @foreach($campaigns as $campaign)
                <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                    {{ Str::limit($campaign->title, 30) }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
        </div>
        <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm rounded-lg transition">Filter</button>
        <a href="{{ route('admin.donations.index') }}" class="px-5 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm rounded-lg transition">Reset</a>
    </form>

    <!-- TABLE -->
    <div class="bg-gray-800 border border-white/5 rounded-2xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-900/60">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Donor</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Campaign</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Amount</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Tracking ID</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Date</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($donations as $donation)
                    <tr class="hover:bg-white/2 transition">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-gray-200">{{ $donation->user->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $donation->user->email ?? '' }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-300 max-w-40">
                            <span class="truncate block">{{ $donation->campaign->title ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-white">${{ number_format($donation->amount, 2) }}</td>
                        <td class="px-5 py-3.5 text-xs font-mono text-gray-400">
                            {{ $donation->tracking_id ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-400">
                            {{ $donation->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium
                                    @if($donation->status === 'paid') bg-emerald-900/50 text-emerald-400
                                    @elseif($donation->status === 'pending') bg-amber-900/50 text-amber-400
                                    @else bg-red-900/50 text-red-400 @endif">
                                {{ $donation->status === 'paid' ? 'Approved' : ucfirst($donation->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-center gap-2">
                                @if($donation->status === 'pending')
                                <form method="POST" action="{{ route('admin.donations.approve', $donation->id) }}" class="inline">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg transition">
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.donations.reject', $donation->id) }}" class="inline">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-red-700 hover:bg-red-800 text-white text-xs font-medium rounded-lg transition">
                                        Reject
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('admin.donations.show', $donation) }}"
                                    class="px-3 py-1.5 bg-white/5 hover:bg-white/10 text-gray-400 text-xs rounded-lg transition">
                                    View
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-500">No donations found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-white/5 bg-gray-900/30">
            {{ $donations->links() }}
        </div>
    </div>

</x-admin-layout>