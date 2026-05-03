<x-guest-layout>

<div class="w-full max-w-md mx-auto">

    <form method="POST" action="{{ route('login') }}" 
          class="bg-white/60 backdrop-blur-xl border border-white/40 
                 shadow-2xl rounded-2xl p-8">
        @csrf

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
        <h2 class="text-2xl font-bold text-blue-900 mb-1 text-center">
            Welcome Back 👋
        </h2>

        <p class="text-center text-gray-500 text-sm mb-6">
            Welcome to CharityHub
        </p>

        <!-- ERRORS -->
        @if($errors->any())
            <div class="mb-4 text-red-500 text-sm text-center">
                Invalid email or password
            </div>
        @endif

        <!-- EMAIL -->
        <div>
            <label class="block text-sm text-blue-900 mb-1">Email</label>

            <input 
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required autofocus
                class="w-full px-4 py-3 rounded-xl bg-white/80 border border-gray-200"
            />
        </div>

        <!-- PASSWORD + 👁 -->
        <div class="mt-4 relative">
            <label class="block text-sm text-blue-900 mb-1">Password</label>

            <input 
                id="password"
                type="password"
                name="password"
                required
                class="w-full px-4 py-3 rounded-xl bg-white/80 border border-gray-200"
            />

            <button type="button"
                onclick="togglePassword()"
                class="absolute right-3 top-10 text-gray-500">
                👁
            </button>
        </div>

        <!-- REMEMBER -->
        <div class="flex items-center mt-4">
            <input type="checkbox" name="remember"
                   class="rounded border-gray-300 text-blue-600">
            <span class="ms-2 text-sm text-gray-600">Remember me</span>
        </div>

        <!-- ACTIONS -->
        <div class="flex items-center justify-between mt-6">

            <div class="flex gap-4 text-sm">
                <a href="{{ route('register') }}" 
                   class="text-gray-500 hover:text-blue-900">
                    Create account
                </a>

                <a href="{{ route('password.request') }}" 
                   class="text-gray-500 hover:text-red-500">
                    Forgot?
                </a>
            </div>

            <button type="submit"
                class="px-6 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-900 text-white">
                Log In
            </button>

        </div>

    </form>

</div>

<script>
function togglePassword() {
    let input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

</x-guest-layout>