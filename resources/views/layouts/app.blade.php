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
                        "background-light": "#f6f8f8",
                        "background-dark": "#121d20",
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
        .active-nav {
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
                    <div class="relative hidden md:block">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input
                            class="w-64 pl-11 pr-4 py-2.5 bg-background-light dark:bg-white/5 border-none rounded-lg focus:ring-2 focus:ring-primary text-sm"
                            placeholder="Search..." type="text" />
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