<x-admin-layout>
    @section('title', 'Users')
    @section('page-title', 'User Management')
    @section('page-subtitle', 'Manage user accounts and roles')

    <!-- FILTER -->
    <form method="GET" class="bg-gray-800 border border-white/5 rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-44">
            <label class="block text-xs text-gray-400 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Name or email..."
                   class="w-full bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-xs text-gray-400 mb-1">Role</label>
            <select name="role" class="bg-gray-900 border border-white/10 text-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">All Roles</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition">Filter</button>
        <a href="{{ route('admin.users.index') }}" class="px-5 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 text-sm rounded-lg transition">Reset</a>
    </form>

    <!-- TABLE -->
    <div class="bg-gray-800 border border-white/5 rounded-2xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-900/60">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">User</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Role</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Donations</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase">Joined</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/2 transition">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-200">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                                    {{ $user->role === 'admin' ? 'bg-indigo-900/50 text-indigo-400' : 'bg-gray-700 text-gray-400' }}">
                                    {{ ucfirst($user->role ?? 'user') }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-300">
                                {{ $user->donations_count ?? 0 }} approved
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-400">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="p-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition text-xs px-3 py-1.5">
                                        View
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.role', $user) }}">
                                            @csrf
                                            <input type="hidden" name="role" value="{{ $user->role === 'admin' ? 'user' : 'admin' }}">
                                            <button class="text-xs px-3 py-1.5 rounded-lg
                                                {{ $user->role === 'admin' ? 'bg-red-900/30 text-red-400 hover:bg-red-900/50' : 'bg-indigo-900/30 text-indigo-400 hover:bg-indigo-900/50' }} transition">
                                                {{ $user->role === 'admin' ? 'Revoke Admin' : 'Make Admin' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-500">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-white/5 bg-gray-900/30">
            {{ $users->links() }}
        </div>
    </div>

</x-admin-layout>
