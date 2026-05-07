<x-admin-layout>
    @section('title', 'Edit Campaign')
    @section('page-title', 'Edit Campaign')
    @section('page-subtitle', 'Update campaign details and settings')

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('admin.campaigns.update', $campaign) }}" enctype="multipart/form-data"
              class="bg-gray-800 border border-white/5 rounded-2xl p-6 space-y-6 shadow-lg">
            @csrf
            @method('PUT')

            <div class="grid sm:grid-cols-2 gap-5">
                <!-- Title -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Campaign Title</label>
                    <input type="text" name="title" value="{{ old('title', $campaign->title) }}" required
                           class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 @error('title') border-red-500 @enderror">
                    @error('title')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Description</label>
                    <textarea name="description" rows="4" required
                              class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $campaign->description) }}</textarea>
                    @error('description')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Goal Amount -->
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Goal Amount ($)</label>
                    <input type="number" name="goal_amount" value="{{ old('goal_amount', $campaign->goal_amount) }}" min="1" step="0.01" required
                           class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <!-- Current Amount -->
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Current Amount ($)</label>
                    <input type="number" name="current_amount" value="{{ old('current_amount', $campaign->current_amount) }}" min="0" step="0.01" required
                           class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <!-- Deadline -->
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline', $campaign->deadline?->format('Y-m-d')) }}"
                           class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Status</label>
                    <select name="status" required
                            class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        @foreach(['active', 'completed', 'expired', 'draft'] as $s)
                            <option value="{{ $s }}" {{ old('status', $campaign->status) === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Image -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Campaign Image</label>
                    @if($campaign->image)
                        <img src="{{ asset('storage/'.$campaign->image) }}" class="w-32 h-20 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="w-full text-gray-400 text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t border-white/5">
                <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition">
                    Save Changes
                </button>
                <a href="{{ route('admin.campaigns.index') }}"
                   class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm rounded-xl transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</x-admin-layout>
