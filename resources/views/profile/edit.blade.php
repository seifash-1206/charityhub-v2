<x-app-layout>
    <!-- HERO SECTION -->
    <div class="relative w-full h-64 md:h-72 overflow-hidden bg-gradient-to-br from-primary-900 via-primary-800 to-primary-900">
        <!-- Background Image -->
        <div class="absolute inset-0 opacity-25"
            style="background-image: url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200&q=80');
                    background-size: cover;
                    background-position: center;">
        </div>

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-primary-900/50 via-primary-900/70 to-primary-900/90"></div>

        <!-- Hero Content -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white px-4 max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-3 drop-shadow-lg">
                    👤 Profile Settings
                </h1>
                <p class="text-lg md:text-xl text-gray-100 drop-shadow-md">
                    Manage your account and security settings
                </p>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="glass-card sm:rounded-2xl p-4 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="glass-card sm:rounded-2xl p-4 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="glass-card sm:rounded-2xl p-4 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>