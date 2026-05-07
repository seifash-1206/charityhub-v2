<x-admin-layout>
    @section('title', $campaign->title)
    @section('page-title', $campaign->title)
    @section('page-subtitle', 'Campaign details and statistics')

    <div class="grid lg:grid-cols-3 gap-6">

        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-5">

            <!-- Campaign Card -->
            <div class="bg-gray-800 border border-white/5 rounded-2xl overflow-hidden shadow-lg">
                @if($campaign->image)
                    <img src="{{ asset('storage/'.$campaign->image) }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-white">{{ $campaign->title }}</h2>
                            <p class="text-sm text-gray-400 mt-0.5">by {{ $campaign->user->name ?? 'N/A' }}</p>
                        </div>
                        <span class="text-xs px-3 py-1.5 rounded-full font-semibold
                            @if($campaign->status === 'active') bg-emerald-900/50 text-emerald-400
                            @elseif($campaign->status === 'completed') bg-blue-900/50 text-blue-400
                            @elseif($campaign->status === 'expired') bg-red-900/50 text-red-400
                            @else bg-gray-700 text-gray-400 @endif">
                            {{ ucfirst($campaign->status) }}
                        </span>
                    </div>
                    <p class="text-gray-300 text-sm leading-relaxed">{{ $campaign->description }}</p>
                </div>
            </div>

            <!-- Recent Donations -->
            <div class="bg-gray-800 border border-white/5 rounded-2xl overflow-hidden shadow-lg">
                <div class="px-6 py-4 border-b border-white/5">
                    <h3 class="text-sm font-semibold text-white">Donations ({{ $campaign->donations->count() }})</h3>
                </div>
                <div class="divide-y divide-white/5">
                    @forelse($campaign->donations as $donation)
                        <div class="px-6 py-3 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-300">{{ $donation->user->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">{{ $donation->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
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
                        <div class="px-6 py-8 text-center text-gray-500 text-sm">No donations yet</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-5">

            <!-- Progress Card -->
            <div class="bg-gray-800 border border-white/5 rounded-2xl p-5 shadow-lg">
                <h3 class="text-sm font-semibold text-white mb-4">Funding Progress</h3>
                @php $percent = $campaign->getProgressPercentage(); @endphp
                <div class="text-center mb-4">
                    <p class="text-3xl font-bold text-white">${{ number_format($campaign->current_amount, 0) }}</p>
                    <p class="text-gray-400 text-sm">of ${{ number_format($campaign->goal_amount, 0) }} goal</p>
                </div>
                <div class="h-2 bg-gray-700 rounded-full overflow-hidden mb-2">
                    <div class="h-2 bg-indigo-500 rounded-full" style="width: {{ $percent }}%"></div>
                </div>
                <p class="text-center text-xs text-gray-400">{{ number_format($percent, 1) }}% funded</p>
            </div>

            <!-- Meta Info -->
            <div class="bg-gray-800 border border-white/5 rounded-2xl p-5 shadow-lg space-y-3">
                <h3 class="text-sm font-semibold text-white mb-3">Campaign Info</h3>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Deadline</span>
                    <span class="text-gray-200">{{ $campaign->deadline?->format('M d, Y') ?? '—' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Volunteers</span>
                    <span class="text-gray-200">{{ $campaign->volunteers->count() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Created</span>
                    <span class="text-gray-200">{{ $campaign->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Slug</span>
                    <span class="text-gray-400 text-xs font-mono">{{ $campaign->slug }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-gray-800 border border-white/5 rounded-2xl p-5 shadow-lg space-y-2">
                <h3 class="text-sm font-semibold text-white mb-3">Actions</h3>
                <a href="{{ route('admin.campaigns.edit', $campaign) }}"
                   class="block w-full text-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition">
                    Edit Campaign
                </a>
                <a href="{{ route('campaigns.show', $campaign) }}" target="_blank"
                   class="block w-full text-center px-4 py-2.5 bg-white/5 hover:bg-white/10 text-gray-300 text-sm rounded-xl transition">
                    View Public Page
                </a>
                <!-- Status Override -->
                <form method="POST" action="{{ route('admin.campaigns.status', $campaign) }}">
                    @csrf
                    <div class="flex gap-2">
                        <select name="status"
                                class="flex-1 bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-3 py-2 text-xs">
                            @foreach(['active', 'completed', 'expired', 'draft'] as $s)
                                <option value="{{ $s }}" {{ $campaign->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded-xl transition">
                            Set
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>
