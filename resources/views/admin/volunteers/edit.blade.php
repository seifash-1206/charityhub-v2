<x-admin-layout>
    @section('title', 'Edit Volunteer')
    @section('page-title', 'Edit Volunteer')
    @section('page-subtitle', $volunteer->name)

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.volunteers.update', $volunteer) }}"
              class="bg-gray-800 border border-white/5 rounded-2xl p-6 space-y-5 shadow-lg">
            @csrf @method('PUT')

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Name</label>
                    <input type="text" name="name" value="{{ old('name', $volunteer->name) }}" required
                           class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $volunteer->email) }}" required
                           class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 @error('email') border-red-500 @enderror">
                    @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $volunteer->phone) }}"
                           class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Campaign</label>
                    <select name="campaign_id" required
                            class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        @foreach($campaigns as $c)
                            <option value="{{ $c->id }}" {{ old('campaign_id', $volunteer->campaign_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Status</label>
                    <select name="status" required
                            class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        @foreach(['active', 'inactive', 'pending'] as $s)
                            <option value="{{ $s }}" {{ old('status', $volunteer->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Availability</label>
                    <select name="availability" required
                            class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        @foreach(['weekdays' => 'Weekdays', 'weekends' => 'Weekends', 'both' => 'Both', 'flexible' => 'Flexible'] as $val => $label)
                            <option value="{{ $val }}" {{ old('availability', $volunteer->availability) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Hours Logged</label>
                    <input type="number" name="hours_logged" value="{{ old('hours_logged', $volunteer->hours_logged) }}" min="0" step="0.5"
                           class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Skills</label>
                    <input type="text" name="skills" value="{{ old('skills', $volunteer->skills) }}"
                           placeholder="e.g. Web development, Event planning..."
                           class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Notes</label>
                    <textarea name="notes" rows="3"
                              class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">{{ old('notes', $volunteer->notes) }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t border-white/5">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition">
                    Save Changes
                </button>
                <a href="{{ route('admin.volunteers.index') }}" class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm rounded-xl transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</x-admin-layout>
