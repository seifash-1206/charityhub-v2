<x-app-layout>
    @section('title', 'Create Campaign — CharityHub')

    <div class="max-w-2xl mx-auto px-4 py-12">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Create Campaign</h1>
            <p class="text-gray-500 text-sm mt-2">Start a new fundraising campaign.</p>
        </div>

        <form method="POST" action="{{ route('campaigns.store') }}" enctype="multipart/form-data"
              class="bg-white/70 backdrop-blur-xl border border-white/60 shadow-xl rounded-2xl p-8 space-y-6">
            @csrf

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
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm @error('title') border-red-400 @enderror">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description *</label>
                <textarea name="description" rows="4" required
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Goal Amount (USD) *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-medium">$</span>
                        <input type="number" name="goal_amount" value="{{ old('goal_amount') }}" min="1" step="0.01" required
                               class="w-full pl-7 pr-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline') }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Campaign Image</label>
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-blue-300 transition">
                    <input type="file" name="image" id="image" accept="image/*"
                           class="hidden" onchange="previewImage(this)">
                    <label for="image" class="cursor-pointer">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-500">Click to upload image</p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF up to 2MB</p>
                    </label>
                    <img id="image-preview" class="hidden mx-auto mt-3 rounded-xl max-h-40 object-cover" alt="Preview">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all">
                    Create Campaign 🚀
                </button>
                <a href="{{ route('campaigns.index') }}"
                   class="px-5 py-3.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>

        </form>
    </div>

    <script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>

</x-app-layout>