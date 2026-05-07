<x-app-layout>
    @section('title', 'Become a Volunteer — CharityHub')

    <div class="max-w-2xl mx-auto px-4 py-12">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-100 mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Become a Volunteer</h1>
            <p class="text-gray-500 text-sm mt-2">Join our team and help make a real impact in our community.</p>
        </div>

        <form method="POST" action="{{ route('volunteers.store') }}"
              class="bg-white/70 backdrop-blur-xl border border-white/60 shadow-xl rounded-2xl p-8 space-y-5">
            @csrf

            <!-- Validation Errors -->
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
                <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm @error('name') border-red-400 @enderror">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm @error('email') border-red-400 @enderror">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="+1 (555) 000-0000"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                <!-- Campaign -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Campaign to Support *</label>
                    <select name="campaign_id" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm @error('campaign_id') border-red-400 @enderror">
                        <option value="">Select a campaign</option>
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                {{ $campaign->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Availability -->
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Availability *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach(['weekdays' => 'Weekdays', 'weekends' => 'Weekends', 'both' => 'Both', 'flexible' => 'Flexible'] as $val => $label)
                            <label class="relative">
                                <input type="radio" name="availability" value="{{ $val }}"
                                       {{ old('availability', 'flexible') === $val ? 'checked' : '' }}
                                       class="peer sr-only">
                                <div class="px-3 py-2.5 rounded-xl border-2 border-gray-200 cursor-pointer text-center text-sm font-medium text-gray-600
                                            peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:border-blue-300 transition">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Skills -->
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Skills & Expertise</label>
                    <input type="text" name="skills" value="{{ old('skills') }}"
                           placeholder="e.g. Photography, Web design, Event planning..."
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                <!-- Notes -->
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Why do you want to volunteer?</label>
                    <textarea name="notes" rows="3"
                              placeholder="Tell us a bit about yourself and your motivation..."
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all">
                    Register as Volunteer
                </button>
                <a href="{{ route('volunteers.index') }}"
                   class="px-5 py-3.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</x-app-layout>