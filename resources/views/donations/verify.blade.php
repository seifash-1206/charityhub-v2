<x-app-layout>
    @section('title', 'Donation Verified — ' . $donation->tracking_id)
    @section('meta_description', 'Verified donation certificate for ' . ($donation->user->name ?? 'Donor') . ' to ' . ($donation->campaign->title ?? 'CharityHub Campaign'))

    <div class="max-w-xl mx-auto px-4 py-12">

        @if($donation->status === 'paid')
            <!-- VERIFIED STATE -->
            <div class="bg-white/70 backdrop-blur-xl border border-white/60 shadow-2xl rounded-2xl overflow-hidden">

                <!-- Top Banner -->
                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-5 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-white/20 rounded-full mb-3">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-white mb-1">Donation Verified ✓</h1>
                    <p class="text-emerald-100 text-sm">This donation has been officially verified by CharityHub</p>
                </div>

                <!-- Logo & Branding -->
                <div class="text-center pt-6 pb-2">
                    <div class="flex items-center justify-center gap-2 mb-1">
                        <img src="{{ asset('images/charity-hub-logo.png') }}" alt="CharityHub" class="h-6 w-6 rounded object-cover">
                        <span class="font-bold text-blue-900 text-base">charity<span class="text-blue-500">hub</span></span>
                    </div>
                    <p class="text-xs text-gray-400">Certificate of Donation</p>
                </div>

                <!-- Details -->
                <div class="px-8 py-6 space-y-4">
                    <div class="text-center">
                        <p class="text-gray-500 text-sm">This certifies that</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $donation->user->name ?? 'Anonymous' }}</p>
                        <p class="text-gray-500 text-sm mt-1">has generously donated</p>
                        <p class="text-4xl font-bold text-blue-700 mt-2">${{ number_format($donation->amount, 2) }}</p>
                        <p class="text-gray-500 text-sm mt-1">to the campaign</p>
                        <p class="text-xl font-semibold text-gray-800 mt-1">{{ $donation->campaign->title ?? '—' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tracking ID</span>
                            <span class="font-mono font-semibold text-blue-600">{{ $donation->tracking_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Date</span>
                            <span class="text-gray-700">{{ $donation->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="text-emerald-600 font-semibold">✓ Verified</span>
                        </div>
                    </div>

                    <!-- QR CODE SECTION -->
                    <div class="text-center mt-6">
                        <p class="text-xs text-gray-400 mb-2 uppercase tracking-wider font-semibold">Verification QR Code</p>
                        <div class="inline-block p-2 bg-white rounded-xl shadow-sm border border-gray-100">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(route('donations.verify', $donation->tracking_id)) }}" 
                                 alt="QR Code" class="w-24 h-24">
                        </div>
                    </div>
                </div>

                <div class="px-8 pb-8 text-center">
                    <p class="text-xs text-gray-400 mb-4">Thank you for your generosity and making a difference.</p>
                    @if($donation->campaign)
                        <a href="{{ route('campaigns.show', $donation->campaign) }}"
                           class="inline-block px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                            View Campaign
                        </a>
                    @endif
                </div>

            </div>
        @else
            <!-- UNVERIFIED STATE -->
            <div class="bg-white/70 backdrop-blur-xl border border-white/60 shadow-xl rounded-2xl p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-800 mb-2">Not Yet Verified</h1>
                <p class="text-gray-500 text-sm">
                    This donation ({{ $donation->tracking_id }}) is currently
                    <strong>{{ $donation->status }}</strong> and has not been approved yet.
                </p>
                <a href="{{ route('donations.track') }}" class="inline-block mt-5 text-sm text-blue-600 hover:underline">
                    ← Back to Track
                </a>
            </div>
        @endif

    </div>

</x-app-layout>
