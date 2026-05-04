<x-app-layout>

<div class="max-w-6xl mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold mb-6">📊 Donations Management</h1>

    <!-- SUCCESS -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- ERROR -->
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- TABLE -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">

        <table class="w-full text-left border-collapse">
            
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">User</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Campaign</th>
                    <th class="p-3">Amount</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Date</th>
                    <th class="p-3 text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($donations as $d)
                    <tr class="border-t hover:bg-gray-50">

                        <td class="p-3 font-semibold">
                            #{{ $d->id }}
                        </td>

                        <td class="p-3">
                            {{ $d->user->name ?? 'N/A' }}
                        </td>

                        <td class="p-3">
                            {{ $d->user->email ?? 'N/A' }}
                        </td>

                        <td class="p-3">
                            {{ $d->campaign->title ?? 'N/A' }}
                        </td>

                        <td class="p-3 text-green-600 font-bold">
                            ${{ number_format($d->amount, 2) }}
                        </td>

                        <!-- STATUS BADGE -->
                        <td class="p-3">
                            @if($d->status === 'paid')
                                <span class="px-2 py-1 bg-green-200 text-green-800 rounded text-sm">
                                    Approved
                                </span>
                            @elseif($d->status === 'pending')
                                <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded text-sm">
                                    Pending
                                </span>
                            @else
                                <span class="px-2 py-1 bg-red-200 text-red-800 rounded text-sm">
                                    Rejected
                                </span>
                            @endif
                        </td>

                        <td class="p-3 text-sm text-gray-500">
                            {{ $d->created_at->format('Y-m-d H:i') }}
                        </td>

                        <!-- ACTIONS -->
                        <td class="p-3 text-center space-x-2">

                            @if($d->status === 'pending')

                                <!-- APPROVE -->
                                <form method="POST"
                                      action="{{ route('admin.donations.approve', $d->id) }}"
                                      class="inline">
                                    @csrf
                                    <button 
                                        class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                        Approve
                                    </button>
                                </form>

                                <!-- REJECT -->
                                <form method="POST"
                                      action="{{ route('admin.donations.reject', $d->id) }}"
                                      class="inline">
                                    @csrf
                                    <button 
                                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                        Reject
                                    </button>
                                </form>

                            @else
                                <span class="text-gray-400 text-sm">
                                    No actions
                                </span>
                            @endif

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center p-6 text-gray-500">
                            No donations found
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

</x-app-layout>