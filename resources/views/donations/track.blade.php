<x-app-layout>
    @section('title', 'Track Your Donation — CharityHub')
    @section('meta_description', 'Track your donation in real-time using your unique tracking ID. Verify your contribution to CharityHub campaigns.')

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
                    🔍 Track Your Donation
                </h1>
                <p class="text-lg md:text-xl text-gray-100 drop-shadow-md">
                    Verify your contribution status and donation details
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-12">

        <!-- SEARCH FORM -->
        <form method="POST" action="{{ route('donations.track.search') }}"
            class="glass-card rounded-2xl p-7 mb-8">
            @csrf
            <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Donation Tracking ID</label>
            <div class="flex gap-3">
                <input type="text"
                    name="tracking_id"
                    value="{{ old('tracking_id') }}"
                    placeholder="e.g. DON-2026-ABC123XYZ"
                    required
                    class="flex-1 px-4 py-3 rounded-xl border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-800 dark:text-white text-primary-900 placeholder-secondary-400 dark:placeholder-secondary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-600 focus:border-transparent text-sm font-mono @error('tracking_id') border-red-400 @enderror">
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-primary-900 to-primary-800 dark:from-primary-800 dark:to-primary-700 text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
                    Track
                </button>
            </div>
            @error('tracking_id')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
            <p class="text-xs text-secondary-500 dark:text-secondary-400 mt-3">
                Your tracking ID was included in your donation confirmation email.
            </p>
        </form>

        <!-- RESULT -->
        @if(isset($donation))
        <div class="glass-card rounded-2xl overflow-hidden">

            <!-- STATUS BANNER -->
            <div class="px-6 py-4
                    @if($donation->status === 'paid') bg-gradient-to-r from-emerald-500 to-emerald-600
                    @elseif($donation->status === 'pending') bg-gradient-to-r from-amber-500 to-amber-600
                    @else bg-gradient-to-r from-red-500 to-red-600 @endif">
                <div class="flex items-center gap-3">
                    @if($donation->status === 'paid')
                    <svg class="w-6 h-6 text-white flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-bold text-white">Donation Confirmed</p>
                        <p class="text-white/80 text-xs">Your donation has been approved and processed.</p>
                    </div>
                    @elseif($donation->status === 'pending')
                    <svg class="w-6 h-6 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-bold text-white">Pending Approval</p>
                        <p class="text-white/80 text-xs">Your donation is being reviewed by our team.</p>
                    </div>
                    @else
                    <svg class="w-6 h-6 text-white flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-bold text-white">Donation Rejected</p>
                        <p class="text-white/80 text-xs">This donation was not approved.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- DONATION DETAILS -->
            <div class="p-6 space-y-4">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-secondary-500 dark:text-secondary-400 uppercase tracking-wider mb-1">Donor</p>
                        <p class="text-primary-900 dark:text-white font-medium">{{ $donation->user->name ?? 'Anonymous' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-secondary-500 dark:text-secondary-400 uppercase tracking-wider mb-1">Amount</p>
                        <p class="text-2xl font-bold text-primary-700 dark:text-primary-400">${{ number_format($donation->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-secondary-500 dark:text-secondary-400 uppercase tracking-wider mb-1">Campaign</p>
                        <p class="text-primary-900 dark:text-white font-medium">{{ $donation->campaign->title ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-secondary-500 dark:text-secondary-400 uppercase tracking-wider mb-1">Date</p>
                        <p class="text-secondary-700 dark:text-secondary-300">{{ $donation->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>

                <hr class="border-secondary-200 dark:border-secondary-700">

                <div class="space-y-2">
                    <div>
                        <p class="text-xs font-semibold text-secondary-500 dark:text-secondary-400 uppercase tracking-wider mb-1">Tracking ID</p>
                        <p class="font-mono text-sm text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/40 px-3 py-2 rounded-lg inline-block">
                            {{ $donation->tracking_id }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-secondary-500 dark:text-secondary-400 uppercase tracking-wider mb-1">Transaction ID</p>
                        <p class="font-mono text-xs text-secondary-600 dark:text-secondary-400 break-all">{{ $donation->transaction_id ?? '—' }}</p>
                    </div>
                </div>

                <!-- Action Links -->
                <div class="flex gap-3 pt-2">
                    @if($donation->status === 'paid')
                    <a href="{{ route('donations.verify', $donation->tracking_id) }}"
                        class="flex-1 text-center px-4 py-2.5 bg-primary-900 dark:bg-primary-800 text-white text-sm font-semibold rounded-xl hover:bg-primary-800 dark:hover:bg-primary-700 transition">
                        🔗 Shareable Verification Link
                    </a>
                    @endif
                    @auth
                    @if($donation->user_id === auth()->id() && $donation->status === 'paid')
                    <a href="{{ route('donations.receipt', $donation->id) }}"
                        class="flex-1 text-center px-4 py-2.5 bg-secondary-200 dark:bg-secondary-700 text-secondary-700 dark:text-secondary-300 text-sm font-semibold rounded-xl hover:bg-secondary-300 dark:hover:bg-secondary-600 transition">
                        📄 Download Certificate
                    </a>
                    @endif
                    @endauth
                </div>
            </div>
        </div>
        @endif

    </div>

</x-app-layout>