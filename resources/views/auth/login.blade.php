<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Bill Everything') }} - Login</title>
    
    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-background-light dark:bg-background-dark text-[#121617] dark:text-white min-h-screen flex items-center justify-center">
    <div class="flex min-h-screen w-full overflow-hidden">
        <!-- Left Side: Visual/Abstract Branding -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-primary items-center justify-center overflow-hidden">
            <!-- Decorative Background Pattern -->
            <div class="absolute inset-0 z-0 opacity-40">
                <div class="absolute top-0 left-0 w-full h-full"
                    style="background: radial-gradient(circle at 20% 30%, #ffffff 0%, transparent 50%), radial-gradient(circle at 80% 70%, #121617 0%, transparent 50%);">
                </div>
            </div>
            <div class="relative z-10 p-12 max-w-lg">
                <div class="mb-8 flex items-center gap-3">
                    <div class="size-10 bg-white rounded-lg flex items-center justify-center text-primary shadow-xl">
                        <span class="material-symbols-outlined text-3xl">cloud_sync</span>
                    </div>
                    <span class="text-white text-2xl font-black tracking-tight">Bill Everything</span>
                </div>
                <h1 class="text-white text-5xl font-black leading-tight tracking-[-0.033em] mb-6">
                    Billing everything with ease.
                </h1>
                <p class="text-white/80 text-lg font-medium leading-relaxed">
                    Unified domain management, high-performance hosting, and enterprise-grade email services.
                </p>
            </div>
            <!-- Bottom Left Label -->
            <div class="absolute bottom-10 left-12 text-white/50 text-sm font-medium">
                © {{ date('Y') }} Bill Everything.
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div
            class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 md:p-16 lg:p-24 bg-white dark:bg-background-dark">
            <div class="w-full max-w-[420px]">
                <!-- Logo for Mobile/Small Screens -->
                <div class="lg:hidden flex items-center gap-2 mb-10">
                    <div class="size-8 bg-primary rounded flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-xl">cloud_sync</span>
                    </div>
                    <span class="text-[#121617] dark:text-white text-xl font-bold">Bill Everything</span>
                </div>

                <!-- PageHeading -->
                <div class="mb-10">
                    <h2 class="text-[#121617] dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">
                        Sign in</h2>
                    <p class="text-[#667f85] dark:text-gray-400 text-base font-normal mt-2">Enter your credentials to
                        access the admin portal.</p>
                </div>

                <!-- Session Status -->
                <div class="mb-4">
                    <x-auth-session-status :status="session('status')" />
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div class="flex flex-col gap-2">
                        <label for="email"
                            class="text-[#121617] dark:text-gray-200 text-sm font-bold leading-normal">Email
                            Address</label>
                        <div class="relative">
                            <input id="email" name="email"
                                class="form-input flex w-full rounded-lg text-[#121617] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-primary h-14 placeholder:text-[#667f85] px-4 text-base font-normal"
                                placeholder="name@company.com" type="email" value="{{ old('email') }}" required
                                autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <label for="password"
                                class="text-[#121617] dark:text-gray-200 text-sm font-bold leading-normal">Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-primary text-sm font-bold hover:underline"
                                    href="{{ route('password.request') }}">Forgot password?</a>
                            @endif
                        </div>
                        <div class="flex w-full items-stretch rounded-lg group">
                            <input id="password" name="password"
                                class="form-input flex w-full min-w-0 flex-1 rounded-lg text-[#121617] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-primary h-14 placeholder:text-[#667f85] p-[15px] rounded-r-none border-r-0 pr-2 text-base font-normal"
                                placeholder="••••••••" type="password" required autocomplete="current-password" />
                            <div
                                class="text-[#667f85] flex border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 items-center justify-center pr-[15px] rounded-r-lg border-l-0 cursor-pointer">
                                <span class="material-symbols-outlined text-xl"
                                    onclick="togglePassword()">visibility</span>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center gap-3">
                        <input name="remember"
                            class="size-5 rounded border-[#dce3e4] text-primary focus:ring-primary cursor-pointer"
                            id="remember" type="checkbox" />
                        <label class="text-[#667f85] dark:text-gray-400 text-sm font-medium cursor-pointer"
                            for="remember">Remember this device</label>
                    </div>

                    <!-- Primary Button -->
                    <button
                        class="w-full flex h-14 items-center justify-center overflow-hidden rounded-lg bg-primary text-white text-base font-bold leading-normal tracking-wide transition-all hover:bg-primary/90 hover:shadow-lg active:scale-[0.98]"
                        type="submit">
                        Sign In to Dashboard
                    </button>
                </form>



                <!-- Footer -->
                <p class="mt-12 text-center text-[#667f85] dark:text-gray-400 text-sm">
                    Don't have an admin account?
                    <a class="text-primary font-bold hover:underline" href="{{ route('register') }}">Create One</a>
                </p>
            </div>

            <!-- Secondary Navigation -->
            <div class="absolute bottom-8 flex gap-6">
                <a class="text-[#667f85] dark:text-gray-500 text-xs font-medium hover:text-primary" href="#">Service
                    Status</a>
                <a class="text-[#667f85] dark:text-gray-500 text-xs font-medium hover:text-primary"
                    href="#">Documentation</a>
                <a class="text-[#667f85] dark:text-gray-500 text-xs font-medium hover:text-primary" href="#">Privacy
                    Policy</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
        }
    </script>
</body>

</html>