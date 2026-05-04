<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-white via-blue-50 to-blue-100 p-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Donations Management
            </h1>
            <p class="text-gray-500 text-sm">
                Manage and control all donations in the system
            </p>
        </div>
    </div>

    <!-- ALERTS -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl shadow">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl shadow">
            {{ session('error') }}
        </div>
    @endif

    <!-- TABLE CARD -->
    <div class="backdrop-blur-lg bg-white/70 border border-white/40 shadow-xl rounded-2xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <!-- HEADER -->
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="p-4">ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Campaign</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="divide-y">

                @forelse($donations as $donation)

                    <tr class="hover:bg-gray-50 transition">

                        <!-- ID -->
                        <td class="p-4 font-bold text-gray-700">
                            #{{ $donation->id }}
                        </td>

                        <!-- USER -->
                        <td class="font-medium text-gray-800">
                            {{ $donation->user->name ?? 'N/A' }}
                        </td>

                        <!-- EMAIL -->
                        <td class="text-gray-500">
                            {{ $donation->user->email ?? '-' }}
                        </td>

                        <!-- CAMPAIGN -->
                        <td class="text-gray-700">
                            {{ $donation->campaign->title ?? '-' }}
                        </td>

                        <!-- AMOUNT -->
                        <td class="font-semibold text-blue-600">
                            ${{ number_format($donation->amount, 2) }}
                        </td>

                        <!-- STATUS -->
                        <td>
                            @if($donation->status === 'pending')
                                <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                    Pending
                                </span>
                            @elseif($donation->status === 'paid')
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    Approved
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                    Rejected
                                </span>
                            @endif
                        </td>

                        <!-- ACTIONS -->
                        <td class="text-center space-x-2">

                            @if($donation->status === 'pending')

                                <!-- APPROVE -->
                                <form method="POST"
                                      action="{{ route('admin.donations.approve', $donation->id) }}"
                                      class="inline">
                                    @csrf
                                    <button
                                        class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 transition shadow-sm">
                                        Approve
                                    </button>
                                </form>

                                <!-- REJECT -->
                                <form method="POST"
                                      action="{{ route('admin.donations.reject', $donation->id) }}"
                                      class="inline">
                                    @csrf
                                    <button
                                        class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700 transition shadow-sm">
                                        Reject
                                    </button>
                                </form>

                            @else
                                <span class="text-gray-400 text-sm">
                                    No action
                                </span>
                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center p-8 text-gray-500">
                            No donations found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <!-- PAGINATION -->
        <div class="p-4 border-t bg-white/60">
            {{ $donations->links() }}
        </div>

    </div>

</div>

</x-app-layout>