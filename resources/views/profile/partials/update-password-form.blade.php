<section>
    <div class="p-8 border-b border-gray-100 dark:border-gray-800">
        <h2 class="text-[#121617] dark:text-white text-xl font-bold tracking-tight">{{ __('Security') }}</h2>
        <p class="text-[#667f85] dark:text-[#a1b0b4] text-sm mt-1">
            {{ __('Manage your account authentication and password.') }}</p>
    </div>

    <div class="p-8 space-y-6">
        <form method="post" action="{{ route('password.update') }}" class="space-y-6 max-w-md">
            @csrf
            @method('put')

            <div class="flex flex-col gap-1.5">
                <label for="update_password_current_password"
                    class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Current Password') }}</label>
                <input id="update_password_current_password" name="current_password" type="password"
                    class="w-full h-12 rounded-lg border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white"
                    placeholder="••••••••••••" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="update_password_password"
                    class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('New Password') }}</label>
                <input id="update_password_password" name="password" type="password"
                    class="w-full h-12 rounded-lg border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white"
                    placeholder="Min. 8 characters" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="update_password_password_confirmation"
                    class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Confirm New Password') }}</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    class="w-full h-12 rounded-lg border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white"
                    placeholder="Repeat new password" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 transition-all shadow-sm">
                    {{ __('Change Password') }}
                </button>

                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>
</section>