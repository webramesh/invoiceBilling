<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Bill Everything') }} - Register Business</title>
    
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
                        <span class="material-symbols-outlined text-3xl">domain</span>
                    </div>
                    <span class="text-white text-2xl font-black tracking-tight">Bill Everything</span>
                </div>
                <h1 class="text-white text-5xl font-black leading-tight tracking-[-0.033em] mb-6">
                    Create your Business Account.
                </h1>
                <p class="text-white/80 text-lg font-medium leading-relaxed">
                    Start managing your domains, hosting, and enterprise-grade email services today.
                </p>
            </div>
            <!-- Bottom Left Label -->
            <div class="absolute bottom-10 left-12 text-white/50 text-sm font-medium">
                © {{ date('Y') }} Bill Everything.
            </div>
        </div>

        <!-- Right Side: Registration Form -->
        <div
            class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 md:p-16 lg:px-24 bg-white dark:bg-background-dark h-full overflow-y-auto">
            <div class="w-full max-w-[500px] py-10">
                <!-- Logo for Mobile/Small Screens -->
                <div class="lg:hidden flex items-center gap-2 mb-10">
                    <div class="size-8 bg-primary rounded flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-xl">domain</span>
                    </div>
                    <span class="text-[#121617] dark:text-white text-xl font-bold">Bill Everything</span>
                </div>

                <!-- PageHeading -->
                <div class="mb-8">
                    <h2 class="text-[#121617] dark:text-white text-3xl font-black leading-tight tracking-[-0.033em]">
                        Register
                    </h2>
                    <p class="text-[#667f85] dark:text-gray-400 text-base font-normal mt-2">
                        Set up your company profile to get started.
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Name Field -->
                        <div class="flex flex-col gap-2">
                            <label for="name" class="text-[#121617] dark:text-gray-200 text-sm font-bold leading-normal">
                                Contact Person
                            </label>
                            <div class="relative">
                                <input id="name" name="name"
                                    class="form-input flex w-full rounded-lg text-[#121617] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-primary h-12 placeholder:text-[#667f85] px-4 text-sm font-normal"
                                    placeholder="e.g. John Doe" type="text" value="{{ old('name') }}" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Company Name -->
                        <div class="flex flex-col gap-2">
                            <label for="company_name" class="text-[#121617] dark:text-gray-200 text-sm font-bold leading-normal">
                                Business Name
                            </label>
                            <div class="relative">
                                <input id="company_name" name="company_name"
                                    class="form-input flex w-full rounded-lg text-[#121617] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-primary h-12 placeholder:text-[#667f85] px-4 text-sm font-normal"
                                    placeholder="e.g. Acme Corp" type="text" value="{{ old('company_name') }}" required autocomplete="organization" />
                                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="flex flex-col gap-2">
                        <label for="phone" class="text-[#121617] dark:text-gray-200 text-sm font-bold leading-normal">
                            Phone Number
                        </label>
                        <div class="relative">
                            <input id="phone" name="phone"
                                class="form-input flex w-full rounded-lg text-[#121617] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-primary h-12 placeholder:text-[#667f85] px-4 text-sm font-normal"
                                placeholder="+1 (555) 000-0000" type="tel" value="{{ old('phone') }}" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="flex flex-col gap-2">
                        <label for="email" class="text-[#121617] dark:text-gray-200 text-sm font-bold leading-normal">
                            Email Address
                        </label>
                        <div class="relative">
                            <input id="email" name="email"
                                class="form-input flex w-full rounded-lg text-[#121617] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-primary h-12 placeholder:text-[#667f85] px-4 text-sm font-normal"
                                placeholder="name@company.com" type="email" value="{{ old('email') }}" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Password Field -->
                        <div class="flex flex-col gap-2">
                            <label for="password" class="text-[#121617] dark:text-gray-200 text-sm font-bold leading-normal">
                                Password
                            </label>
                            <div class="flex w-full items-stretch rounded-lg group text-sm">
                                <input id="password" name="password"
                                    class="form-input flex w-full min-w-0 flex-1 rounded-lg text-[#121617] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-primary h-12 placeholder:text-[#667f85] p-[15px] rounded-r-none border-r-0 pr-2 font-normal"
                                    placeholder="••••••••" type="password" required autocomplete="new-password" />
                                <div class="text-[#667f85] flex border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 items-center justify-center pr-[15px] rounded-r-lg border-l-0 cursor-pointer">
                                    <span class="material-symbols-outlined text-lg" onclick="togglePassword('password')">visibility</span>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="flex flex-col gap-2">
                            <label for="password_confirmation" class="text-[#121617] dark:text-gray-200 text-sm font-bold leading-normal">
                                Confirm Password
                            </label>
                            <div class="flex w-full items-stretch rounded-lg group text-sm">
                                <input id="password_confirmation" name="password_confirmation"
                                    class="form-input flex w-full min-w-0 flex-1 rounded-lg text-[#121617] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 focus:border-primary h-12 placeholder:text-[#667f85] p-[15px] rounded-r-none border-r-0 pr-2 font-normal"
                                    placeholder="••••••••" type="password" required autocomplete="new-password" />
                                <div class="text-[#667f85] flex border border-[#dce3e4] dark:border-gray-700 bg-white dark:bg-gray-800 items-center justify-center pr-[15px] rounded-r-lg border-l-0 cursor-pointer">
                                    <span class="material-symbols-outlined text-lg" onclick="togglePassword('password_confirmation')">visibility</span>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-center gap-3">
                         <input id="terms" type="checkbox" required class="size-4 rounded border-[#dce3e4] text-primary focus:ring-primary cursor-pointer">
                         <label for="terms" class="text-[#667f85] dark:text-gray-400 text-xs font-medium cursor-pointer">
                            By creating an account, you agree to our 
                            <a href="#" class="text-primary hover:underline">Terms of Service</a> and 
                            <a href="#" class="text-primary hover:underline">Privacy Policy</a>.
                         </label>
                    </div>

                    <!-- Primary Button -->
                    <button
                        class="w-full flex h-14 items-center justify-center overflow-hidden rounded-lg bg-primary text-white text-base font-bold leading-normal tracking-wide transition-all hover:bg-primary/90 hover:shadow-lg active:scale-[0.98] gap-2"
                        type="submit">
                        Register Business
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </button>
                </form>

                <!-- Footer -->
                <p class="mt-10 text-center text-[#667f85] dark:text-gray-400 text-sm">
                    Already have an account?
                    <a class="text-primary font-bold hover:underline" href="{{ route('login') }}">Sign In</a>
                </p>
                
                <!-- Footer Links -->
                 <div class="mt-8 flex justify-center gap-6">
                    <a class="text-[#667f85] dark:text-gray-500 text-xs font-medium hover:text-primary" href="#">Service Status</a>
                    <a class="text-[#667f85] dark:text-gray-500 text-xs font-medium hover:text-primary" href="#">Documentation</a>
                    <a class="text-[#667f85] dark:text-gray-500 text-xs font-medium hover:text-primary" href="#">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id); // Use passed id or fallback
            // If id isn't passed (as in login) it might just check 'password', but here we pass specific IDs
             // Actually let's make it robust
             const el = document.getElementById(id);
            if(el) {
                 const type = el.getAttribute('type') === 'password' ? 'text' : 'password';
                 el.setAttribute('type', type);
            }
        }
    </script>
</body>
</html>
