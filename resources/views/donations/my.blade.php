<x-app-layout>
    @section('title', 'My Donations — CharityHub')

    <!-- HERO SECTION -->
    <div class="relative w-full h-64 md:h-72 overflow-hidden bg-gradient-to-br from-primary-900 via-primary-800 to-primary-900">
        <!-- Background Image -->
        <div class="absolute inset-0 opacity-25"
            style="background-image: url('https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=1200&q=80');
                    background-size: cover;
                    background-position: center;">
        </div>

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-primary-900/50 via-primary-900/70 to-primary-900/90"></div>

        <!-- Hero Content -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white px-4 max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-3 drop-shadow-lg">
                    ❤️ Your Donations
                </h1>
                <p class="text-lg md:text-xl text-gray-100 drop-shadow-md">
                    Track and manage your charitable contributions
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-primary-900 dark:text-white">Donation History</h2>
                <p class="text-secondary-600 dark:text-secondary-400 text-sm mt-1">Your donation history and certificates</p>
            </div>
            <a href="{{ route('donations.track') }}"
                class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition font-medium">
                Track by ID →
            </a>
        </div>

        <!-- DONATIONS LIST -->
        @if($donations->count())
        <div class="space-y-4">
            @foreach($donations as $donation)
            <div class="glass-card hover:shadow-md rounded-2xl p-5 transition-shadow">
                <div class="flex items-start gap-4">

                    <!-- Status Icon -->
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                                @if($donation->status === 'paid') bg-emerald-100 dark:bg-emerald-900/40
                                @elseif($donation->status === 'pending') bg-amber-100 dark:bg-amber-900/40
                                @else bg-red-100 dark:bg-red-900/40 @endif">
                        @if($donation->status === 'paid')
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        @elseif($donation->status === 'pending')
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @else
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-primary-900 dark:text-white">{{ $donation->campaign->title ?? 'Campaign Removed' }}</p>
                                <p class="text-xs text-secondary-500 dark:text-secondary-400 font-mono mt-0.5">{{ $donation->tracking_id }}</p>
                                <p class="text-xs text-secondary-500 dark:text-secondary-400 mt-1">{{ $donation->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-xl font-bold text-primary-700 dark:text-primary-400">${{ number_format($donation->amount, 2) }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                            @if($donation->status === 'paid') bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400
                                            @elseif($donation->status === 'pending') bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400
                                            @else bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 @endif">
                                    {{ $donation->status === 'paid' ? 'Approved' : ucfirst($donation->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        @if($donation->status === 'paid')
                        <div class="flex gap-3 mt-3">
                            <a href="{{ route('donations.receipt', $donation->id) }}"
                                class="text-xs px-3 py-1.5 bg-primary-100 dark:bg-primary-900/40 text-primary-600 dark:text-primary-400 hover:bg-primary-200 dark:hover:bg-primary-900/60 rounded-lg transition font-medium">
                                📄 Certificate
                            </a>
                            <a href="{{ route('donations.verify', $donation->tracking_id) }}"
                                class="text-xs px-3 py-1.5 bg-secondary-100 dark:bg-secondary-800 text-secondary-600 dark:text-secondary-400 hover:bg-secondary-200 dark:hover:bg-secondary-700 rounded-lg transition font-medium">
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
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary-100 dark:bg-primary-900/40 mb-6">
                <svg class="w-10 h-10 text-primary-400 dark:text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-primary-900 dark:text-white mb-2">No donations yet</h2>
            <p class="text-secondary-600 dark:text-secondary-400 text-sm mb-6">Your donation history will appear here once you make your first donation.</p>
            <a href="{{ route('campaigns.index') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-900 to-primary-800 dark:from-primary-800 dark:to-primary-700 text-white text-sm font-semibold rounded-xl hover:shadow-lg transition">
                Browse Campaigns →
            </a>
        </div>
        @endif

    </div>

</x-app-layout>