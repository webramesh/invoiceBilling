<section>
    <div class="p-8 border-b border-gray-100 dark:border-gray-800">
        <h2 class="text-[#121617] dark:text-white text-xl font-bold tracking-tight">{{ __('Profile Information') }}</h2>
        <p class="text-[#667f85] dark:text-[#a1b0b4] text-sm mt-1">
            {{ __("Update your account's profile information and email address.") }}</p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <div class="p-8 space-y-8">
        <!-- Avatar Section (Visual only for now if no backend support) -->
        <div class="flex flex-col md:flex-row items-start md:items-center gap-8">
            <div class="relative group">
                <div
                    class="size-24 rounded-full bg-center bg-cover bg-primary/10 border-4 border-background-light dark:border-background-dark shadow-sm flex items-center justify-center overflow-hidden">
                    <span class="material-symbols-outlined text-4xl text-primary/40">person</span>
                </div>
                <button
                    class="absolute -bottom-1 -right-1 bg-white dark:bg-gray-700 shadow-md rounded-full size-8 flex items-center justify-center border border-gray-100 dark:border-gray-600 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-lg">edit</span>
                </button>
            </div>
            <div class="flex flex-col gap-2">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Profile Picture</h3>
                <div class="flex gap-3">
                    <button type="button"
                        class="px-4 py-2 bg-primary text-white rounded-lg text-xs font-bold hover:bg-primary/90 transition-all">Upload
                        New</button>
                    <button type="button"
                        class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-lg text-xs font-bold hover:bg-gray-200 transition-all">Remove</button>
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Recommended: Square image, at least 400x400px.</p>
            </div>
        </div>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="flex flex-col gap-1.5">
                    <label for="name"
                        class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Full Name') }}</label>
                    <input id="name" name="name" type="text"
                        class="w-full h-12 rounded-lg border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white"
                        value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-1.5">
                    <label for="email"
                        class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Email Address') }}</label>
                    <input id="email" name="email" type="email"
                        class="w-full h-12 rounded-lg border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white"
                        value="{{ old('email', $user->email) }}" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification"
                                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 transition-all shadow-sm">
                    {{ __('Save Changes') }}
                </button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>
</section>