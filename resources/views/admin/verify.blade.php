<x-guest-layout>

<div class="w-full max-w-md mx-auto">

    <div class="bg-white/60 backdrop-blur-xl border border-white/40 
                shadow-2xl rounded-2xl p-8">

        <!-- LOGO -->
        <div class="flex justify-center mb-4">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
                    C
                </div>
                <span class="font-bold text-xl text-blue-700">
                    CharityHub
                </span>
            </div>
        </div>

        <!-- TITLE -->
        <h2 class="text-2xl font-bold text-blue-900 text-center">
            Admin Verification 🔐
        </h2>

        <p class="text-center text-gray-500 text-sm mb-6">
            Enter your admin key to continue
        </p>

        <!-- ERROR -->
        @if($errors->any())
            <div class="mb-4 text-red-500 text-sm text-center">
                Invalid credentials
            </div>
        @endif

        <!-- FORM -->
            <form method="POST" action="{{ route('admin.verify.post') }}">
            @csrf

            <!-- ADMIN KEY -->
            <div>
                <label class="block text-sm text-blue-900 mb-1">
                    Admin Key
                </label>

                <div class="relative">
                    <input 
                        id="admin_key"
                        type="password"
                        name="admin_key"
                        required

                        class="w-full px-4 py-3 rounded-xl 
                        bg-white/80 border border-gray-200 
                        text-gray-900 placeholder-gray-400
                        focus:outline-none focus:ring-2 focus:ring-blue-500"

                        placeholder="Enter admin key"
                    >

                    <!-- 👁 SHOW/HIDE -->
                    <button type="button"
                        onclick="toggleKey()"
                        class="absolute right-3 top-3 text-gray-500">
                        👁
                    </button>
                </div>
            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                class="w-full mt-6 px-6 py-3 rounded-xl 
                bg-gradient-to-r from-blue-600 to-blue-900 
                text-white font-semibold 
                shadow-md 
                hover:scale-105 hover:shadow-lg 
                transition duration-300"
            >
                Verify Access
            </button>

        </form>

    </div>

</div>

<!-- 🔥 SCRIPT -->
<script>
function toggleKey() {
    const input = document.getElementById('admin_key');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

</x-guest-layout>