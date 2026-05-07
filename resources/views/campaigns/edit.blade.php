<x-app-layout>
    @section('title', 'Edit Campaign — CharityHub')

    <div class="max-w-2xl mx-auto px-4 py-12">

        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Campaign</h1>

        <form method="POST" action="{{ route('campaigns.update', $campaign) }}" enctype="multipart/form-data"
              class="bg-white/70 backdrop-blur-xl border border-white/60 shadow-xl rounded-2xl p-8 space-y-6">
            @csrf @method('PUT')

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Campaign Title *</label>
                <input type="text" name="title" value="{{ old('title', $campaign->title) }}" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description *</label>
                <textarea name="description" rows="4" required
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none">{{ old('description', $campaign->description) }}</textarea>
            </div>

            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Goal Amount (USD) *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-medium">$</span>
                        <input type="number" name="goal_amount" value="{{ old('goal_amount', $campaign->goal_amount) }}" min="1" step="0.01" required
                               class="w-full pl-7 pr-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline', $campaign->deadline?->format('Y-m-d')) }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Campaign Image</label>
                @if($campaign->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/'.$campaign->image) }}"
                             class="h-32 rounded-xl object-cover" alt="Current image">
                        <p class="text-xs text-gray-400 mt-1">Current image. Upload a new one to replace it.</p>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    Save Changes
                </button>
                <a href="{{ route('campaigns.show', $campaign) }}"
                   class="px-5 py-3.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</x-app-layout>