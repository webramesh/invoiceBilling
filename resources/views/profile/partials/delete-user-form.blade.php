<section>
    <div class="p-8 bg-danger/5 border-b border-danger/10">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-danger">warning</span>
            <h2 class="text-danger text-xl font-black tracking-tight uppercase italic">{{ __('Danger Zone') }}</h2>
        </div>
        <p class="text-[#667f85] dark:text-[#a1b0b4] text-sm mt-1">
            {{ __('Irreversible actions regarding your account data. Please proceed with extreme caution.') }}</p>
    </div>

    <div class="p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-6 flex-1">
            <!-- Deactivate Account (Visual/Placeholder as requested by UI) -->
            <div class="flex flex-col gap-1">
                <h4 class="text-sm font-bold text-gray-800 dark:text-white">Deactivate Account</h4>
                <p class="text-xs text-gray-500">Temporarily disable your account access. You can reactivate later via
                    support.</p>
                <button type="button"
                    class="mt-3 w-fit px-4 py-2 border border-danger text-danger text-xs font-bold rounded-lg hover:bg-danger hover:text-white transition-all">Deactivate</button>
            </div>

            <hr class="border-danger/10" />

            <!-- Delete Account -->
            <div class="flex flex-col gap-1">
                <h4 class="text-sm font-bold text-gray-800 dark:text-white">{{ __('Delete All Data') }}</h4>
                <p class="text-xs text-gray-500">
                    {{ __('Permanently delete all hosting, domains, and personal data. This cannot be undone.') }}</p>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    class="mt-3 w-fit px-4 py-2 bg-danger text-white text-xs font-bold rounded-lg hover:bg-danger/80 transition-all shadow-md">
                    {{ __('Delete Forever') }}
                </button>
            </div>
        </div>

        <div class="hidden lg:block w-48 opacity-10 grayscale select-none pointer-events-none">
            <span class="material-symbols-outlined text-[120px] text-danger">delete_forever</span>
        </div>
    </div>

    <!-- Modal remains for Laravel functionality -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>