<x-app-layout>

<div class="max-w-3xl mx-auto px-6 py-12">

    <!-- HEADER -->
    <h1 class="text-3xl font-bold text-gray-800 mb-10 text-center">
        Create Campaign 🚀
    </h1>

    <!-- FORM CARD -->
    <div class="backdrop-blur-xl bg-white/60 border border-white/40 
                shadow-2xl rounded-2xl p-8 transition duration-300">

        <form method="POST" action="{{ route('campaigns.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- TITLE -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Campaign Title
                </label>
                <input name="title"
                    placeholder="Enter campaign title"
                    class="w-full px-4 py-3 rounded-xl 
                    bg-white/70 border border-gray-200 
                    focus:ring-2 focus:ring-blue-500 focus:outline-none 
                    transition duration-200 shadow-sm"
                    required>
            </div>

            <!-- DESCRIPTION -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description
                </label>
                <textarea name="description"
                    placeholder="Describe your campaign..."
                    rows="4"
                    class="w-full px-4 py-3 rounded-xl 
                    bg-white/70 border border-gray-200 
                    focus:ring-2 focus:ring-blue-500 focus:outline-none 
                    transition duration-200 shadow-sm"
                    required></textarea>
            </div>

            <!-- GOAL -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Goal Amount ($)
                </label>
                <input name="goal_amount"
                    type="number"
                    placeholder="1000"
                    class="w-full px-4 py-3 rounded-xl 
                    bg-white/70 border border-gray-200 
                    focus:ring-2 focus:ring-blue-500 focus:outline-none 
                    transition duration-200 shadow-sm"
                    required>
            </div>

            <!-- DEADLINE -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Deadline
                </label>
                <input name="deadline"
                    type="date"
                    class="w-full px-4 py-3 rounded-xl 
                    bg-white/70 border border-gray-200 
                    focus:ring-2 focus:ring-blue-500 focus:outline-none 
                    transition duration-200 shadow-sm">
            </div>

            <!-- BUTTON -->
            <div class="pt-4 flex justify-end">
                <button 
                    class="px-6 py-3 rounded-xl 
                    bg-gradient-to-r from-blue-600 to-blue-900 
                    text-white font-semibold shadow-md 
                    hover:scale-105 hover:shadow-xl transition duration-300">
                    Create Campaign
                </button>
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">
                    Campaign Image
                </label>

                <input 
                    type="file" 
                    name="image" 
                    class="w-full px-4 py-3 rounded-xl bg-white/70 border border-gray-200"
                >
            </div>

        </form>

    </div>

</div>

</x-app-layout>