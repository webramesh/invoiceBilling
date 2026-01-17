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
    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .sidebar-item-active {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border-left: 4px solid white !important;
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
                class="sticky top-0 z-40 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-[#e4e7eb] dark:border-white/10 px-8 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <form action="{{ url()->current() }}" method="GET"
                        class="bg-gray-100 dark:bg-white/5 rounded-lg flex items-center px-3 py-2 w-80">
                        @foreach(request()->except('search') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <span class="material-symbols-outlined text-gray-400 text-xl">search</span>
                        <input name="search" value="{{ request('search') }}"
                            class="bg-transparent border-none focus:ring-0 text-sm w-full placeholder:text-gray-400 dark:text-white"
                            placeholder="Search records..." type="text" />
                    </form>
                </div>

                <div class="flex items-center gap-4">
                    <button
                        class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg relative">
                        <span class="material-symbols-outlined">notifications</span>
                        <span
                            class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-background-dark"></span>
                    </button>
                    <div class="h-8 w-[1px] bg-gray-200 dark:bg-white/10"></div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center gap-3 transition-all focus:outline-none group">
                            <div class="text-right hidden lg:block">
                                <p class="text-sm font-bold leading-none text-[#121617] dark:text-white">
                                    {{ Auth::user()->name }}
                                </p>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">Super Admin</p>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center border-2 border-primary/20 group-hover:border-primary/40 transition-colors">
                                <span class="material-symbols-outlined text-primary">person</span>
                            </div>
                        </button>

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

            <!-- Page Title Area (Optional slot for page-specific headers) -->
            @isset($header)
                <div class="px-8 pt-8">
                    {{ $header }}
                </div>
            @endisset

            <!-- Page Content -->
            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>