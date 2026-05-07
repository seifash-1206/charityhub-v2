<x-app-layout>
    @section('title', $campaign->title . ' — CharityHub')
    @section('meta_description', Str::limit($campaign->description, 160))

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- BACK -->
        <a href="{{ route('campaigns.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 transition mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Campaigns
        </a>

        <div class="grid lg:grid-cols-5 gap-8">

            <!-- LEFT: Main Content -->
            <div class="lg:col-span-3 space-y-6">

                <!-- IMAGE -->
                @if($campaign->image)
                    <div class="rounded-2xl overflow-hidden shadow-xl aspect-video">
                        <img src="{{ asset('storage/'.$campaign->image) }}"
                             alt="{{ $campaign->title }}"
                             class="w-full h-full object-cover">
                    </div>
                @endif

                <!-- HEADER -->
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $campaign->getStatusBadgeClass() }} flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $campaign->getStatusDotClass() }}"></span>
                            {{ ucfirst($campaign->status) }}
                        </span>
                        @if($campaign->deadline)
                            <span class="text-xs text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">
                                @if($campaign->isExpired())
                                    Ended {{ $campaign->deadline->format('M d, Y') }}
                                @else
                                    Ends {{ $campaign->deadline->diffForHumans() }}
                                @endif
                            </span>
                        @endif
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 leading-tight">{{ $campaign->title }}</h1>
                    <p class="text-gray-500 text-sm mt-2">by {{ $campaign->user->name ?? 'CharityHub' }}</p>
                </div>

                <!-- DESCRIPTION -->
                <div class="bg-white/60 backdrop-blur border border-white/60 rounded-2xl p-6 shadow-sm">
                    <h2 class="font-semibold text-gray-800 mb-3 text-sm uppercase tracking-wider">About this Campaign</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $campaign->description }}</p>
                </div>

                <!-- RECENT DONORS -->
                @if($campaign->donations->count() > 0)
                    <div class="bg-white/60 backdrop-blur border border-white/60 rounded-2xl p-5 shadow-sm">
                        <h2 class="font-semibold text-gray-800 mb-4 text-sm uppercase tracking-wider">Recent Supporters</h2>
                        <div class="space-y-3">
                            @foreach($campaign->donations as $d)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-sm font-bold flex-shrink-0">
                                        A
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800">Anonymous Donor</p>
                                        <p class="text-xs text-gray-400">{{ $d->created_at->diffForHumans() }}</p>
                                    </div>
                                    <p class="text-sm font-bold text-blue-700">${{ number_format($d->amount, 0) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- RIGHT: Donation Sidebar -->
            <div class="lg:col-span-2 space-y-5">

                <!-- PROGRESS CARD -->
                <div class="bg-white/70 backdrop-blur-xl border border-white/60 shadow-xl rounded-2xl p-6 sticky top-20">

                    <!-- Amount -->
                    <div class="mb-5">
                        <p class="text-3xl font-bold text-gray-900">${{ number_format($campaign->current_amount, 0) }}</p>
                        <p class="text-gray-400 text-sm mt-1">raised of ${{ number_format($campaign->goal_amount, 0) }} goal</p>
                    </div>

                    <!-- Progress Bar -->
                    @php $progress = $campaign->getProgressPercentage(); @endphp
                    <div class="mb-3">
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-3 rounded-full transition-all duration-700
                                @if($campaign->status === 'completed') bg-gradient-to-r from-blue-500 to-blue-600
                                @elseif($campaign->status === 'expired') bg-gray-400
                                @else bg-gradient-to-r from-blue-500 to-indigo-600 @endif"
                                 style="width: {{ min($progress, 100) }}%">
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1.5 text-right">{{ number_format($progress, 1) }}% funded</p>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <p class="text-lg font-bold text-gray-900">{{ $campaign->donations->count() }}</p>
                            <p class="text-xs text-gray-400">Donors</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            @if($campaign->deadline)
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $campaign->isExpired() ? 0 : $campaign->deadline->diffInDays(now()) }}
                                </p>
                                <p class="text-xs text-gray-400">Days left</p>
                            @else
                                <p class="text-lg font-bold text-gray-900">∞</p>
                                <p class="text-xs text-gray-400">No deadline</p>
                            @endif
                        </div>
                    </div>

                    <!-- DONATION FORM or BLOCKED STATE -->
                    @if($campaign->isAcceptingDonations())
                        @auth
                            <form action="{{ route('donations.store', $campaign) }}" method="POST" class="space-y-4">
                                @csrf

                                <!-- Quick Amount Buttons -->
                                <div class="grid grid-cols-3 gap-2 mb-2">
                                    @foreach([10, 25, 50, 100, 250, 500] as $amt)
                                        <button type="button"
                                                onclick="document.getElementById('amount').value='{{ $amt }}'"
                                                class="px-2 py-1.5 text-xs font-semibold rounded-lg border border-blue-200 text-blue-700 bg-blue-50 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition">
                                            ${{ $amt }}
                                        </button>
                                    @endforeach
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Custom Amount (USD)</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-medium text-sm">$</span>
                                        <input type="number"
                                               id="amount"
                                               name="amount"
                                               min="1"
                                               step="0.01"
                                               required
                                               placeholder="Enter amount"
                                               class="w-full pl-7 pr-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800 font-medium @error('amount') border-red-400 @enderror">
                                    </div>
                                    @error('amount')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                        class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.99] transition-all duration-200">
                                    💙 Donate Now
                                </button>

                                <p class="text-xs text-center text-gray-400">
                                    🔒 Secured by Stripe
                                </p>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <p class="text-gray-500 text-sm mb-4">Sign in to support this campaign</p>
                                <a href="{{ route('login') }}"
                                   class="block w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:shadow-lg transition">
                                    Login to Donate
                                </a>
                                <a href="{{ route('register') }}"
                                   class="block w-full py-2.5 mt-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                                    Create Account
                                </a>
                            </div>
                        @endauth
                    @else
                        <!-- Closed State -->
                        <div class="text-center py-5">
                            @if($campaign->status === 'completed')
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-blue-100 mb-3">
                                    <svg class="w-7 h-7 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-blue-700 text-lg">Goal Reached! 🎉</p>
                                <p class="text-gray-400 text-sm mt-1">This campaign has been fully funded.</p>
                            @else
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-red-100 mb-3">
                                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-red-600 text-lg">Campaign Ended</p>
                                <p class="text-gray-400 text-sm mt-1">This campaign's deadline has passed.</p>
                            @endif
                            <a href="{{ route('campaigns.index') }}"
                               class="inline-block mt-4 text-sm text-blue-600 hover:underline">
                                Browse Active Campaigns →
                            </a>
                        </div>
                    @endif

                </div>

                <!-- SHARE / ADMIN ACTIONS -->
                @can('update', $campaign)
                    <div class="flex gap-2">
                        <a href="{{ route('campaigns.edit', $campaign) }}"
                           class="flex-1 text-center px-4 py-2.5 bg-white/70 backdrop-blur border border-white/60 text-gray-700 text-sm font-medium rounded-xl hover:bg-white transition shadow-sm">
                            Edit
                        </a>
                        @can('delete', $campaign)
                            <form method="POST" action="{{ route('campaigns.destroy', $campaign) }}"
                                  onsubmit="return confirm('Delete this campaign?')" class="flex-1">
                                @csrf @method('DELETE')
                                <button class="w-full px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-xl hover:bg-red-100 transition border border-red-100">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                @endcan

            </div>
        </div>

    </div>

</x-app-layout>