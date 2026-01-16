<aside class="w-64 bg-accent-teal text-white/90 flex flex-col shrink-0 shadow-xl z-20 transition-all duration-300">
    <!-- Brand Logo -->
    <div class="p-6 flex items-center gap-3">
        <div
            class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-accent-teal shadow-lg shadow-black/10">
            <span class="material-symbols-outlined font-bold">cloud_sync</span>
        </div>
        <div class="flex flex-col">
            <h1 class="text-white text-base font-bold leading-tight">BillEverything</h1>
            <p class="text-white/60 text-[10px] font-black uppercase tracking-widest">Admin Console</p>
        </div>
    </div>

    <!-- Navigation List -->
    <nav class="flex-1 px-3 space-y-1 py-4">
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('dashboard') ? 'active-nav' : 'hover:bg-accent-teal-dark/50 hover:text-white text-white/80' }}"
            href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-sm font-semibold">Dashboard</span>
        </a>

        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('clients.*') ? 'active-nav' : 'hover:bg-accent-teal-dark/50 hover:text-white text-white/80' }}"
            href="{{ route('clients.index') }}">
            <span class="material-symbols-outlined">group</span>
            <span class="text-sm font-semibold">Clients</span>
        </a>

        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('services.*') || request()->routeIs('service-categories.*') ? 'active-nav' : 'hover:bg-accent-teal-dark/50 hover:text-white text-white/80' }}"
            href="{{ route('services.index') }}">
            <span class="material-symbols-outlined">database</span>
            <span class="text-sm font-semibold">Services</span>
        </a>

        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('subscriptions.*') ? 'active-nav' : 'hover:bg-accent-teal-dark/50 hover:text-white text-white/80' }}"
            href="{{ route('subscriptions.index') }}">
            <span class="material-symbols-outlined">credit_card</span>
            <span class="text-sm font-semibold">Subscriptions</span>
        </a>

        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('invoices.*') ? 'active-nav' : 'hover:bg-accent-teal-dark/50 hover:text-white text-white/80' }}"
            href="{{ route('invoices.index') }}">
            <span class="material-symbols-outlined">description</span>
            <span class="text-sm font-semibold">Invoices</span>
        </a>
    </nav>

    <!-- Sidebar Footer / User Profile -->
    <div class="p-4 border-t border-white/10">
        <div class="flex items-center gap-3 p-2">
            <div
                class="size-8 rounded-full bg-accent-teal-dark flex items-center justify-center border border-white/20">
                <span class="material-symbols-outlined text-sm text-white">person</span>
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-xs font-bold truncate text-white">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-white/60 truncate uppercase font-medium">Administrator</p>
            </div>
            <a href="{{ route('profile.edit') }}"
                class="material-symbols-outlined text-sm cursor-pointer hover:text-white transition-colors">settings</a>
        </div>
    </div>
</aside>