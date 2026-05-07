<x-guest-layout>

<div class="w-full max-w-md mx-auto">

    <form method="POST" action="{{ route('login') }}"
          class="bg-white/60 dark:bg-secondary-800/40 backdrop-blur-xl border border-white/40 dark:border-secondary-700/30
                 shadow-2xl dark:shadow-secondary-900/50 rounded-2xl p-8">
        @csrf

        <!-- LOGO -->
        <div class="flex justify-center mb-4">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-primary-900 dark:bg-primary-700 rounded-lg flex items-center justify-center text-white font-bold">
                    C
                </div>
                <span class="font-bold text-xl text-primary-900 dark:text-primary-400">
                    CharityHub
                </span>
            </div>
        </div>

        <!-- TITLE -->
        <h2 class="text-2xl font-bold text-primary-900 dark:text-white mb-1 text-center">
            Welcome Back 👋
        </h2>

        <p class="text-center text-secondary-600 dark:text-secondary-400 text-sm mb-6">
            Welcome to CharityHub
        </p>

        <!-- ERRORS -->
        @if($errors->any())
            <div class="mb-4 text-red-600 dark:text-red-400 text-sm text-center bg-red-50 dark:bg-red-900/20 px-4 py-2 rounded-lg">
                Invalid email or password
            </div>
        @endif

        <!-- EMAIL -->
        <div>
            <label class="block text-sm text-primary-900 dark:text-secondary-300 mb-2 font-medium">Email</label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required autofocus
                class="w-full px-4 py-3 rounded-xl bg-white/80 dark:bg-secondary-700/50 border border-secondary-200 dark:border-secondary-600 text-secondary-900 dark:text-secondary-100 placeholder-secondary-400 dark:placeholder-secondary-500 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-600 focus:border-transparent dark:focus:border-transparent"
            />
        </div>

        <!-- PASSWORD + 👁 -->
        <div class="mt-4 relative">
            <label class="block text-sm text-primary-900 dark:text-secondary-300 mb-2 font-medium">Password</label>

            <input
                id="password"
                type="password"
                name="password"
                required
                class="w-full px-4 py-3 rounded-xl bg-white/80 dark:bg-secondary-700/50 border border-secondary-200 dark:border-secondary-600 text-secondary-900 dark:text-secondary-100 placeholder-secondary-400 dark:placeholder-secondary-500 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-600 focus:border-transparent dark:focus:border-transparent"
            />

            <button type="button"
                onclick="togglePassword()"
                class="absolute right-3 top-11 text-secondary-500 dark:text-secondary-400 hover:text-secondary-700 dark:hover:text-secondary-200">
                👁
            </button>
        </div>

        <!-- REMEMBER -->
        <div class="flex items-center mt-4">
            <input type="checkbox" name="remember" id="remember"
                   class="rounded border-secondary-300 dark:border-secondary-600 text-primary-900 dark:text-primary-700 dark:bg-secondary-700 focus:ring-primary-500 dark:focus:ring-primary-600">
            <label for="remember" class="ms-2 text-sm text-secondary-600 dark:text-secondary-400 cursor-pointer">Remember me</label>
        </div>

        <!-- ACTIONS -->
        <div class="flex items-center justify-between mt-6">

            <div class="flex gap-4 text-sm">
                <a href="{{ route('register') }}"
                   class="text-secondary-600 dark:text-secondary-400 hover:text-primary-900 dark:hover:text-primary-400 font-medium transition">
                    Create account
                </a>

                <a href="{{ route('password.request') }}"
                   class="text-secondary-600 dark:text-secondary-400 hover:text-red-600 dark:hover:text-red-400 font-medium transition">
                    Forgot?
                </a>
            </div>

            <button type="submit"
                class="px-6 py-2 rounded-xl bg-gradient-to-r from-primary-900 to-primary-800 dark:from-primary-800 dark:to-primary-700 text-white hover:from-primary-800 hover:to-primary-700 dark:hover:from-primary-700 dark:hover:to-primary-600 font-semibold shadow-lg dark:shadow-primary-900/30 transition">
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