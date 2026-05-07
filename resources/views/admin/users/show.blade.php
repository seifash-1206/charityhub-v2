<x-admin-layout>
    @section('title', $user->name)
    @section('page-title', $user->name)
    @section('page-subtitle', 'User profile and donation history')

    <div class="grid lg:grid-cols-3 gap-6 max-w-5xl">

        <!-- User Profile -->
        <div class="bg-gray-800 border border-white/5 rounded-2xl p-6 shadow-lg">
            <div class="text-center mb-5">
                <div class="w-16 h-16 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-2xl font-bold mx-auto mb-3">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="text-lg font-bold text-white">{{ $user->name }}</h2>
                <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                <span class="inline-block mt-2 text-xs px-2.5 py-1 rounded-full
                    {{ $user->role === 'admin' ? 'bg-indigo-900/50 text-indigo-400' : 'bg-gray-700 text-gray-400' }}">
                    {{ ucfirst($user->role ?? 'user') }}
                </span>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Joined</span>
                    <span class="text-gray-200">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Total Donated</span>
                    <span class="text-gray-200 font-semibold">${{ number_format($user->donations->where('status', 'paid')->sum('amount'), 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Donations</span>
                    <span class="text-gray-200">{{ $user->donations->count() }}</span>
                </div>
            </div>

            @if($user->id !== auth()->id())
                <div class="mt-5 pt-4 border-t border-white/5">
                    <form method="POST" action="{{ route('admin.users.role', $user) }}">
                        @csrf
                        <input type="hidden" name="role" value="{{ $user->role === 'admin' ? 'user' : 'admin' }}">
                        <button class="w-full text-sm py-2 rounded-xl
                            {{ $user->role === 'admin' ? 'bg-red-900/30 hover:bg-red-900/50 text-red-400' : 'bg-indigo-900/30 hover:bg-indigo-900/50 text-indigo-400' }} transition">
                            {{ $user->role === 'admin' ? 'Revoke Admin Role' : 'Grant Admin Role' }}
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Donation History -->
        <div class="lg:col-span-2 bg-gray-800 border border-white/5 rounded-2xl overflow-hidden shadow-lg">
            <div class="px-6 py-4 border-b border-white/5">
                <h3 class="text-sm font-semibold text-white">Donation History</h3>
            </div>
            <div class="divide-y divide-white/5">
                @forelse($user->donations as $donation)
                    <div class="px-6 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-300">{{ $donation->campaign->title ?? '—' }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $donation->tracking_id ?? '' }}</p>
                            <p class="text-xs text-gray-600">{{ $donation->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-white">${{ number_format($donation->amount, 2) }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full
                                @if($donation->status === 'paid') bg-emerald-900/50 text-emerald-400
                                @elseif($donation->status === 'pending') bg-amber-900/50 text-amber-400
                                @else bg-red-900/50 text-red-400 @endif">
                                {{ ucfirst($donation->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-gray-500 text-sm">No donations yet</div>
                @endforelse
            </div>
        </div>
    </div>

</x-admin-layout>
