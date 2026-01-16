<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bill Everything') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&amp;display=swap"
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
                        "primary": "#0a192f",
                        "primary-light": "#112240",
                        "accent-teal": "#2492A8",
                        "accent-teal-hover": "#1e7a8c",
                        "accent-teal-dark": "#1b6d7d",
                        "background-light": "#f8fafc",
                        "background-dark": "#020617",
                        "accent-emerald": "#10b981",
                        "accent-red": "#ef4444",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.375rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .active-nav {
            background-color: theme('colors.accent-teal-dark');
            color: white !important;
            border-left: 4px solid white;
        }
    </style>
</head>

<body
    class="bg-background-light dark:bg-background-dark font-display text-[#1e293b] dark:text-slate-200 transition-colors duration-200">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-y-auto">
            <!-- Header -->
            <header
                class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-8 sticky top-0 z-10 font-display">
                <h2 class="text-lg font-bold text-primary dark:text-white">
                    @isset($header)
                        {{ $header }}
                    @else
                        Dashboard Overview
                    @endisset
                </h2>
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <span
                            class="material-symbols-outlined text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-lg">search</span>
                        <input
                            class="pl-10 pr-4 py-1.5 bg-slate-100 dark:bg-slate-800 border-none rounded-lg text-sm w-64 focus:ring-2 focus:ring-accent-teal/30 outline-none"
                            placeholder="Search..." type="text" />
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="flex items-center">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-2 text-slate-500 hover:text-accent-red transition-colors font-bold text-xs uppercase tracking-wider">
                                <span class="material-symbols-outlined text-lg">logout</span>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>