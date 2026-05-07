<x-admin-layout>
    @section('title', $volunteer->name)
    @section('page-title', $volunteer->name)
    @section('page-subtitle', 'Volunteer profile and activity')

    <div class="grid lg:grid-cols-3 gap-6 max-w-4xl">

        <!-- Profile Card -->
        <div class="lg:col-span-2 bg-gray-800 border border-white/5 rounded-2xl p-6 shadow-lg">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($volunteer->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">{{ $volunteer->name }}</h2>
                        <p class="text-gray-400 text-sm">{{ $volunteer->email }}</p>
                        @if($volunteer->phone)
                            <p class="text-gray-500 text-xs mt-0.5">{{ $volunteer->phone }}</p>
                        @endif
                    </div>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $volunteer->getStatusBadgeClass() }}">
                    {{ ucfirst($volunteer->status) }}
                </span>
            </div>

            <div class="grid sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Campaign</p>
                    <p class="text-gray-200">{{ $volunteer->campaign->title ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Availability</p>
                    <p class="text-gray-200">{{ $volunteer->getAvailabilityLabel() }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Hours Logged</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($volunteer->hours_logged, 1) }}h</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Joined</p>
                    <p class="text-gray-200">{{ $volunteer->created_at->format('M d, Y') }}</p>
                </div>
                @if($volunteer->skills)
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500 mb-1">Skills</p>
                        <p class="text-gray-300 text-sm">{{ $volunteer->skills }}</p>
                    </div>
                @endif
                @if($volunteer->notes)
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500 mb-1">Notes</p>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $volunteer->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-4">

            <!-- Log Hours -->
            <div class="bg-gray-800 border border-white/5 rounded-2xl p-5 shadow-lg">
                <h3 class="text-sm font-semibold text-white mb-3">Log Hours</h3>
                <form method="POST" action="{{ route('admin.volunteers.hours', $volunteer) }}">
                    @csrf
                    <div class="flex gap-2">
                        <input type="number" name="hours" min="0.5" max="24" step="0.5"
                               placeholder="Hours" required
                               class="flex-1 bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition">
                            Add
                        </button>
                    </div>
                </form>
            </div>

            <!-- Status -->
            <div class="bg-gray-800 border border-white/5 rounded-2xl p-5 shadow-lg">
                <h3 class="text-sm font-semibold text-white mb-3">Update Status</h3>
                <form method="POST" action="{{ route('admin.volunteers.status', $volunteer) }}">
                    @csrf
                    <div class="flex gap-2">
                        <select name="status"
                                class="flex-1 bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm">
                            @foreach(['active', 'inactive', 'pending'] as $s)
                                <option value="{{ $s }}" {{ $volunteer->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm rounded-lg transition">Set</button>
                    </div>
                </form>
            </div>

            <!-- Edit / Delete -->
            <div class="space-y-2">
                <a href="{{ route('admin.volunteers.edit', $volunteer) }}"
                   class="block w-full text-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition">
                    Edit Profile
                </a>
                <form method="POST" action="{{ route('admin.volunteers.destroy', $volunteer) }}"
                      onsubmit="return confirm('Delete this volunteer?')">
                    @csrf @method('DELETE')
                    <button class="w-full px-4 py-2.5 bg-red-700/30 hover:bg-red-700/50 text-red-400 text-sm rounded-xl transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>
