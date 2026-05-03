<x-app-layout>

<div class="max-w-3xl mx-auto px-6 py-12">

    <!-- HEADER -->
    <h1 class="text-3xl font-bold text-gray-800 mb-10 text-center">
        Edit Campaign ✏️
    </h1>

    <!-- CARD -->
    <div class="backdrop-blur-xl bg-white/60 border border-white/40 
                shadow-2xl rounded-2xl p-8">

        <!-- UPDATE FORM -->
        <form method="POST" 
              action="{{ route('campaigns.update', $campaign) }}" 
              enctype="multipart/form-data"
              class="space-y-6">

            @csrf
            @method('PUT')

            <!-- TITLE -->
            <div>
                <label class="block text-sm text-gray-700 mb-1">Title</label>
                <input name="title"
                    value="{{ old('title', $campaign->title) }}"
                    class="w-full px-4 py-3 rounded-xl bg-white/70 border border-gray-200">
            </div>

            <!-- DESCRIPTION -->
            <div>
                <label class="block text-sm text-gray-700 mb-1">Description</label>
                <textarea name="description"
                    rows="4"
                    class="w-full px-4 py-3 rounded-xl bg-white/70 border border-gray-200">{{ old('description', $campaign->description) }}</textarea>
            </div>

            <!-- GOAL -->
            <div>
                <label class="block text-sm text-gray-700 mb-1">Goal Amount</label>
                <input name="goal_amount"
                    type="number"
                    value="{{ old('goal_amount', $campaign->goal_amount) }}"
                    class="w-full px-4 py-3 rounded-xl bg-white/70 border border-gray-200">
            </div>

            <!-- DEADLINE -->
            <div>
                <label class="block text-sm text-gray-700 mb-1">Deadline</label>
                <input name="deadline"
                    type="date"
                    value="{{ old('deadline', optional($campaign->deadline)->format('Y-m-d')) }}"
                    class="w-full px-4 py-3 rounded-xl bg-white/70 border border-gray-200">
            </div>

            <!-- CURRENT IMAGE -->
            @if($campaign->image)
                <div>
                    <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                    <img src="{{ asset('storage/'.$campaign->image) }}"
                         class="rounded-xl h-40 object-cover mb-3">
                </div>
            @endif

            <!-- NEW IMAGE -->
            <div>
                <label class="block text-sm text-gray-700 mb-1">Replace Image</label>
                <input type="file" name="image"
                    class="w-full px-4 py-3 rounded-xl bg-white/70 border border-gray-200">
            </div>

            <!-- UPDATE BUTTON -->
            <button 
                class="w-full px-6 py-3 rounded-xl 
                bg-gradient-to-r from-blue-600 to-blue-900 
                text-white font-semibold shadow-md 
                hover:scale-105 transition">
                Update Campaign
            </button>

        </form>

        <!-- DELETE FORM (SEPARATE) -->
        <form method="POST" 
              action="{{ route('campaigns.destroy', $campaign) }}" 
              class="mt-6"
              onsubmit="return confirm('Are you sure you want to delete this campaign?');">

            @csrf
            @method('DELETE')

            <button class="w-full py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition">
                Delete Campaign
            </button>

        </form>

    </div>

</div>

</x-app-layout>