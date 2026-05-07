<x-app-layout>
    @section('title', 'Edit Volunteer Profile — CharityHub')

    <div class="max-w-2xl mx-auto px-4 py-12">

        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Volunteer Profile</h1>

        <form method="POST" action="{{ route('volunteers.update', $volunteer) }}"
              class="bg-white/70 backdrop-blur-xl border border-white/60 shadow-xl rounded-2xl p-8 space-y-5">
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

            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $volunteer->name) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $volunteer->phone) }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Campaign *</label>
                    <select name="campaign_id" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        @foreach($campaigns as $c)
                            <option value="{{ $c->id }}" {{ old('campaign_id', $volunteer->campaign_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Availability *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach(['weekdays' => 'Weekdays', 'weekends' => 'Weekends', 'both' => 'Both', 'flexible' => 'Flexible'] as $val => $label)
                            <label class="relative">
                                <input type="radio" name="availability" value="{{ $val }}"
                                       {{ old('availability', $volunteer->availability) === $val ? 'checked' : '' }}
                                       class="peer sr-only">
                                <div class="px-3 py-2.5 rounded-xl border-2 border-gray-200 cursor-pointer text-center text-sm font-medium text-gray-600
                                            peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:border-blue-300 transition">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Skills</label>
                    <input type="text" name="skills" value="{{ old('skills', $volunteer->skills) }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Notes</label>
                    <textarea name="notes" rows="3"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none">{{ old('notes', $volunteer->notes) }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    Save Changes
                </button>
                <a href="{{ route('volunteers.index') }}"
                   class="px-5 py-3.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</x-app-layout>
