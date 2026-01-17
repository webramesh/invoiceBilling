<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bill Easy') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2492a8",
                        "danger": "#BF1E2D",
                        "background-light": "#f9fafa",
                        "background-dark": "#16181d",
                        "accent-emerald": "#10b981",
                        "accent-red": "#ef4444",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .sidebar-item-active {
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 4px solid white;
            color: white !important;
        }
    </style>
</head>

<body
    class="bg-background-light dark:bg-background-dark font-display text-[#121617] dark:text-white antialiased min-h-screen">
    <div class="flex h-full min-h-screen">
        <!-- Sidebar Navigation -->
        @include('layouts.navigation')

        <!-- Main Content Area -->
        <main class="flex-1 ml-64 min-w-0 flex flex-col">
            <!-- Header -->
            <header
                class="bg-white dark:bg-background-dark border-b border-gray-200 dark:border-white/10 px-8 py-6 flex flex-wrap justify-between items-center sticky top-0 z-40">
                @isset($header)
                    {{ $header }}
                @else
                    <div>
                        <h2 class="text-3xl font-black text-[#121617] dark:text-white tracking-tight leading-none">Dashboard
                        </h2>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-2 font-medium">Platform overview and
                            statistics</p>
                    </div>
                @endisset

                <div class="flex items-center gap-4">
                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center gap-3 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 transition-all focus:outline-none">
                            <div
                                class="size-10 bg-primary/10 text-primary rounded-full flex items-center justify-center font-black text-sm border-2 border-white dark:border-slate-800 shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ str_contains(Auth::user()->name, ' ') ? strtoupper(substr(explode(' ', Auth::user()->name)[1], 0, 1)) : '' }}
                            </div>
                            <div class="text-left hidden lg:block pr-2">
                                <p class="text-xs font-bold text-[#121617] dark:text-white leading-tight">
                                    {{ Auth::user()->name }}
                                </p>
                                <p class="text-[10px] font-medium text-gray-500 dark:text-gray-400">Super Admin</p>
                            </div>
                            <span
                                class="material-symbols-outlined text-gray-400 text-lg transition-transform duration-200"
                                :class="{'rotate-180': open}">expand_more</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-3 w-56 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 z-50 overflow-hidden"
                            style="display: none;">

                            <div class="p-4 border-b border-gray-50 dark:border-slate-800">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Account</p>
                                <p class="text-sm font-bold text-[#121617] dark:text-white truncate">
                                    {{ Auth::user()->email }}
                                </p>
                            </div>

                            <div class="py-2">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-lg opacity-60">person</span>
                                    Edit Profile
                                </a>
                                <a href="{{ route('profile.edit') }}#update-password"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-lg opacity-60">lock</span>
                                    Change Password
                                </a>
                                <a href="#"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                    <span class="material-symbols-outlined text-lg opacity-60">settings</span>
                                    Settings
                                </a>
                            </div>

                            <div class="p-2 border-t border-gray-50 dark:border-slate-800">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-3 py-2 text-sm text-red-600 font-bold hover:bg-red-50 dark:hover:bg-red-900/10 rounded-xl transition-colors text-left">
                                        <span class="material-symbols-outlined text-lg">logout</span>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>