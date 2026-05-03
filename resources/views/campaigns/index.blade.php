<x-app-layout>

<div class="max-w-7xl mx-auto px-6 py-10">

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-100/70 backdrop-blur border border-green-200 text-green-700 shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-10">

        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
            Campaigns
        </h1>

        <!-- 🔥 ONLY ADMIN CAN SEE -->
        @can('create', App\Models\Campaign::class)
            <a href="{{ route('campaigns.create') }}"
               class="px-6 py-2 rounded-xl 
               bg-gradient-to-r from-blue-600 to-blue-900 
               text-white font-semibold shadow-md 
               hover:scale-105 hover:shadow-xl transition duration-300">
               + New Campaign
            </a>
        @endcan

    </div>

    <!-- GRID -->
    <div class="grid md:grid-cols-3 gap-8">

        @forelse($campaigns as $campaign)

            <div class="relative backdrop-blur-xl bg-white/60 border border-white/40 
                        shadow-xl rounded-2xl p-6 
                        hover:scale-[1.03] transition duration-300">

                <!-- IMAGE -->
                @if($campaign->image)
                    <img 
                        src="{{ asset('storage/'.$campaign->image) }}"
                        class="rounded-xl mb-4 h-40 w-full object-cover"
                    >
                @endif

                <!-- TITLE -->
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ $campaign->title }}
                </h2>

                <!-- DESC -->
                <p class="text-gray-500 text-sm mt-2 leading-relaxed">
                    {{ $campaign->description }}
                </p>

                <!-- PROGRESS -->
                @php
                    $progress = $campaign->goal_amount > 0 
                        ? ($campaign->current_amount / $campaign->goal_amount) * 100 
                        : 0;
                @endphp

                <div class="mt-5">
                    <div class="w-full bg-gray-200/70 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-800 h-2 rounded-full transition-all duration-500"
                             style="width: {{ min($progress, 100) }}%">
                        </div>
                    </div>
                </div>

                <!-- AMOUNT -->
                <div class="flex justify-between items-center mt-3 text-sm text-gray-600">
                    <span>${{ number_format($campaign->current_amount, 2) }}</span>
                    <span>${{ number_format($campaign->goal_amount, 2) }}</span>
                </div>

                <!-- STATUS -->
                <div class="absolute top-4 right-4 text-xs px-2 py-1 rounded-full 
                    @if($campaign->status === 'completed')
                        bg-green-100 text-green-600
                    @elseif($campaign->status === 'draft')
                        bg-gray-200 text-gray-600
                    @else
                        bg-red-100 text-red-500
                    @endif
                ">
                    {{ ucfirst($campaign->status ?? 'active') }}
                </div>

                <!-- ACTIONS -->
                <div class="flex justify-between items-center mt-5 text-sm">

                    <!-- 🔥 EDIT (OWNER OR ADMIN) -->
                    @can('update', $campaign)
                        <a href="{{ route('campaigns.edit', $campaign) }}"
                           class="text-blue-600 hover:underline">
                           Edit
                        </a>
                    @endcan

                    <!-- 🔥 DELETE (OWNER OR ADMIN) -->
                    @can('delete', $campaign)
                        <form method="POST" action="{{ route('campaigns.destroy', $campaign) }}"
                              onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')

                            <button class="text-red-500 hover:underline">
                                Delete
                            </button>
                        </form>
                    @endcan

                </div>

            </div>

        @empty

            <div class="col-span-3 text-center text-gray-500 text-lg">
                No campaigns yet.
            </div>

        @endforelse

    </div>

</div>

</x-app-layout>