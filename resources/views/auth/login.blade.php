<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Bill Everything') }} - Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&amp;display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2492a8",
                        "background-light": "#f9fafa",
                        "background-dark": "#1a1d23",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
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

                <!-- Divider -->
                <div class="flex items-center gap-4 my-8">
                    <div class="h-px bg-[#f1f3f4] dark:bg-gray-800 flex-1"></div>
                    <span class="text-[#667f85] text-xs font-bold uppercase tracking-widest">or continue with</span>
                    <div class="h-px bg-[#f1f3f4] dark:bg-gray-800 flex-1"></div>
                </div>

                <!-- SSO Options -->
                <div class="flex gap-4">
                    <button
                        class="flex-1 flex items-center justify-center gap-2 h-12 border border-[#dce3e4] dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 hover:bg-background-light dark:hover:bg-gray-700 transition-colors">
                        <svg class="h-5 w-5" viewBox="0 0 24 24">
                            <path
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                fill="#4285F4" />
                            <path
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                fill="#34A853" />
                            <path
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                fill="#FBBC05" />
                            <path
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                fill="#EA4335" />
                        </svg>
                        <span class="text-[#121617] dark:text-white text-sm font-bold">Google</span>
                    </button>
                    <button
                        class="flex-1 flex items-center justify-center gap-2 h-12 border border-[#dce3e4] dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 hover:bg-background-light dark:hover:bg-gray-700 transition-colors">
                        <svg class="h-5 w-5 text-green-500 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.407 3.481 2.242 2.242 3.48 5.23 3.481 8.411-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.3 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884 0 2.225.569 3.843 1.688 5.795l-1.082 3.95 4.095-1.074l-.212-.37zm11.28-12.721c.3-.67.3-.87.45-.1.15-.75.75-1.05 1.2-.45.45.6 1.5 1.5 1.5 2.25s-1.5 3.75-4.5 3.75-4.5-5.25-4.5-5.25.75-.75 1.5-1.5.75-1.05 1.05-1.35.3-.45.15-.9c-.15-.45-.75-1.05-.75-1.05s-.3-.45-.6-.15c-.3.3-1.05 1.05-1.05 1.5s.45 2.1 2.25 4.35 4.8 4.05 5.55 3.75c.75-.3 1.5-1.05 1.8-1.5s.3-.45.15-.75c-.15-.3-.75-.6-1.5-.9z" />
                        </svg>
                        <span class="text-[#121617] dark:text-white text-sm font-bold">WhatsApp</span>
                    </button>
                </div>

                <!-- Footer -->
                <p class="mt-12 text-center text-[#667f85] dark:text-gray-400 text-sm">
                    Don't have an admin account?
                    <a class="text-primary font-bold hover:underline" href="#">Create One</a>
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