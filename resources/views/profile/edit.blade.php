<x-app-layout>
    <div class="max-w-4xl mx-auto px-8 py-12">
        <!-- Page Heading -->
        <header class="mb-10">
            <h1 class="text-[#121617] dark:text-white text-4xl font-black leading-tight tracking-tight">Account Settings
            </h1>
            <p class="text-[#667f85] dark:text-[#a1b0b4] text-lg mt-2 font-normal leading-normal">Manage your domain,
                hosting, and profile preferences.</p>
        </header>

        <div class="space-y-8">
            <!-- Profile Information Card -->
            <div class="bg-white dark:bg-[#1f2228] rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.06)] overflow-hidden">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Security Card -->
            <div class="bg-white dark:bg-[#1f2228] rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.06)] overflow-hidden">
                @include('profile.partials.update-password-form')
            </div>

            <!-- Danger Zone Card -->
            <div class="bg-white dark:bg-[#1f2228] border-2 border-danger rounded-xl overflow-hidden">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

        <footer class="mt-12 text-center text-gray-400 text-xs">
            <p>© 2024 Admin Panel Business Services. All rights reserved.</p>
            <div class="mt-2 space-x-4">
                <a class="hover:underline" href="#">Privacy Policy</a>
                <a class="hover:underline" href="#">Terms of Service</a>
                <a class="hover:underline" href="#">Help Center</a>
            </div>
        </footer>
    </div>
</x-app-layout>