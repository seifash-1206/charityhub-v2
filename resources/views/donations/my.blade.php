<x-app-layout>
    @section('title', 'My Donations — CharityHub')

    <div class="max-w-4xl mx-auto px-4 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Donations</h1>
                <p class="text-gray-500 text-sm mt-1">Your donation history and certificates</p>
            </div>
            <a href="{{ route('donations.track') }}"
               class="text-sm text-blue-600 hover:text-blue-800 transition font-medium">
                Track by ID →
            </a>
        </div>

        <!-- DONATIONS LIST -->
        @if($donations->count())
            <div class="space-y-4">
                @foreach($donations as $donation)
                    <div class="bg-white/70 backdrop-blur-xl border border-white/60 shadow-sm hover:shadow-md rounded-2xl p-5 transition-shadow">
                        <div class="flex items-start gap-4">

                            <!-- Status Icon -->
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                                @if($donation->status === 'paid') bg-emerald-100
                                @elseif($donation->status === 'pending') bg-amber-100
                                @else bg-red-100 @endif">
                                @if($donation->status === 'paid')
                                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @elseif($donation->status === 'pending')
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $donation->campaign->title ?? 'Campaign Removed' }}</p>
                                        <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $donation->tracking_id }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $donation->created_at->format('M d, Y \a\t g:i A') }}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-xl font-bold text-blue-700">${{ number_format($donation->amount, 2) }}</p>
                                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                            @if($donation->status === 'paid') bg-emerald-100 text-emerald-700
                                            @elseif($donation->status === 'pending') bg-amber-100 text-amber-700
                                            @else bg-red-100 text-red-700 @endif">
                                            {{ $donation->status === 'paid' ? 'Approved' : ucfirst($donation->status) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                @if($donation->status === 'paid')
                                    <div class="flex gap-3 mt-3">
                                        <a href="{{ route('donations.receipt', $donation->id) }}"
                                           class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition font-medium">
                                            📄 Certificate
                                        </a>
                                        <a href="{{ route('donations.verify', $donation->tracking_id) }}"
                                           class="text-xs px-3 py-1.5 bg-gray-50 text-gray-600 hover:bg-gray-100 rounded-lg transition font-medium">
                                            🔗 Verify
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $donations->links() }}
            </div>

        @else
            <!-- EMPTY STATE -->
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-700 mb-2">No donations yet</h2>
                <p class="text-gray-400 text-sm mb-6">Your donation history will appear here once you make your first donation.</p>
                <a href="{{ route('campaigns.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-800 text-white text-sm font-semibold rounded-xl hover:shadow-lg transition">
                    Browse Campaigns →
                </a>
            </div>
        @endif

    </div>

</x-app-layout>
