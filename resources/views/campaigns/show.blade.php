<x-app-layout>

<div class="max-w-4xl mx-auto px-6 py-10">

    <!-- TITLE -->
    <h1 class="text-3xl font-bold mb-4">
        {{ $campaign->title }}
    </h1>

    <!-- IMAGE -->
    @if($campaign->image)
        <img src="{{ asset('storage/'.$campaign->image) }}"
             class="w-full h-64 object-cover rounded-xl mb-6">
    @endif

    <!-- DESCRIPTION -->
    <p class="text-gray-600 mb-6">
        {{ $campaign->description }}
    </p>

    <!-- PROGRESS -->
    @php
        $progress = $campaign->goal_amount > 0 
            ? ($campaign->current_amount / $campaign->goal_amount) * 100 
            : 0;
    @endphp

    <div class="mb-4">
        <div class="w-full bg-gray-200 h-3 rounded-full overflow-hidden">
            <div class="bg-blue-600 h-3 rounded-full transition-all"
                 style="width: {{ min($progress, 100) }}%">
            </div>
        </div>
    </div>

    <div class="flex justify-between text-sm text-gray-600 mb-6">
        <span>${{ number_format($campaign->current_amount, 2) }}</span>
        <span>${{ number_format($campaign->goal_amount, 2) }}</span>
    </div>

    <!-- 💰 DONATION FORM -->
    @auth
        <form action="{{ route('donations.store', $campaign) }}" method="POST" class="space-y-4">
            @csrf

            <input 
                type="number" 
                name="amount" 
                min="1"
                step="0.01"
                required
                placeholder="Enter donation amount"
                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
            >

            <button type="submit"
                    class="w-full bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                Donate 💰
            </button>
        </form>
    @else
        <p class="text-gray-600">
            Please 
            <a href="{{ route('login') }}" class="text-blue-600 underline">
                login
            </a> 
            to donate.
        </p>
    @endauth

    <!-- SUCCESS MESSAGE + PRINT -->
    @if(session('success'))
        <div class="mt-6 p-4 bg-green-100 text-green-700 rounded-lg shadow">

            <p class="font-semibold">
                {{ session('success') }}
            </p>

            {{-- 🔥 IMPORTANT: needs last donation id --}}
            @if(session('donation_id'))
                <a href="{{ route('donations.receipt', session('donation_id')) }}"
                   class="inline-block mt-3 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    🧾 Print Receipt
                </a>
            @endif

        </div>
    @endif

    <!-- ERROR MESSAGE -->
    @if(session('error'))
        <div class="mt-6 p-3 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- VALIDATION ERRORS -->
    @if($errors->any())
        <div class="mt-6 p-3 bg-red-100 text-red-700 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

</div>

</x-app-layout>