<x-admin-layout>
    @section('title', 'Donation #' . $donation->tracking_id)
    @section('page-title', 'Donation Detail')
    @section('page-subtitle', $donation->tracking_id ?? '#'.$donation->id)

    <div class="max-w-2xl">
        <div class="bg-gray-800 border border-white/5 rounded-2xl p-6 shadow-lg space-y-5">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tracking ID</p>
                    <p class="font-mono text-indigo-300 font-semibold">{{ $donation->tracking_id ?? '—' }}</p>
                </div>
                <span class="text-sm px-3 py-1.5 rounded-full font-semibold
                    @if($donation->status === 'paid') bg-emerald-900/50 text-emerald-400
                    @elseif($donation->status === 'pending') bg-amber-900/50 text-amber-400
                    @else bg-red-900/50 text-red-400 @endif">
                    {{ ucfirst($donation->status) }}
                </span>
            </div>

            <hr class="border-white/5">

            <!-- Details -->
            <div class="grid sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 text-xs mb-1">Donor</p>
                    <p class="text-gray-200 font-medium">{{ $donation->user->name ?? 'Unknown' }}</p>
                    <p class="text-gray-500 text-xs">{{ $donation->user->email ?? '' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">Amount</p>
                    <p class="text-2xl font-bold text-white">${{ number_format($donation->amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">Campaign</p>
                    <p class="text-gray-200">{{ $donation->campaign->title ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">Date</p>
                    <p class="text-gray-200">{{ $donation->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-gray-500 text-xs mb-1">Transaction ID</p>
                    <p class="font-mono text-xs text-gray-400 break-all">{{ $donation->transaction_id ?? '—' }}</p>
                </div>
            </div>

            <!-- Actions -->
            @if($donation->status === 'pending')
                <div class="flex gap-3 pt-4 border-t border-white/5">
                    <form method="POST" action="{{ route('admin.donations.approve', $donation->id) }}" class="flex-1">
                        @csrf
                        <button class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition">
                            ✓ Approve Donation
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.donations.reject', $donation->id) }}" class="flex-1">
                        @csrf
                        <button class="w-full py-2.5 bg-red-700 hover:bg-red-800 text-white text-sm font-semibold rounded-xl transition">
                            ✕ Reject Donation
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>

</x-admin-layout>
