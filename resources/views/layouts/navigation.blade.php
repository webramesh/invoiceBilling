<aside class="w-64 bg-primary text-white flex flex-col fixed h-full z-[60]">
    <div class="p-6 flex items-center gap-3">
        <div class="bg-white/20 p-2 rounded-lg">
            <span class="material-symbols-outlined text-white text-2xl">rocket_launch</span>
        </div>
        <div>
            <h1 class="text-lg font-bold leading-none">Bill Easy</h1>
            <p class="text-white/70 text-[10px] uppercase tracking-widest mt-1">Infrastructure</p>
        </div>
    </div>

    <nav class="flex-1 mt-6 px-3 space-y-1">
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'sidebar-item-active' : 'hover:bg-white/10' }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-sm font-medium">Dashboard</span>
        </a>

        <a href="{{ route('clients.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('clients.*') ? 'sidebar-item-active' : 'hover:bg-white/10' }}">
            <span class="material-symbols-outlined">group</span>
            <span class="text-sm font-medium">Clients</span>
        </a>

        <a href="{{ route('subscriptions.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('subscriptions.*') ? 'sidebar-item-active' : 'hover:bg-white/10' }}">
            <span class="material-symbols-outlined">receipt_long</span>
            <span class="text-sm font-medium">Subscriptions</span>
        </a>

        <a href="{{ route('services.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('services.*') ? 'sidebar-item-active' : 'hover:bg-white/10' }}">
            <span class="material-symbols-outlined">dns</span>
            <span class="text-sm font-medium">Services</span>
        </a>

        <a href="{{ route('invoices.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('invoices.*') ? 'sidebar-item-active' : 'hover:bg-white/10' }}">
            <span class="material-symbols-outlined">payments</span>
            <span class="text-sm font-medium">Billing</span>
        </a>

        <div class="pt-4 pb-2 px-4">
            <p class="text-white/40 text-[10px] uppercase font-bold tracking-wider">System</p>
        </div>

        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('profile.*') ? 'sidebar-item-active' : 'hover:bg-white/10' }}">
            <span class="material-symbols-outlined">settings</span>
            <span class="text-sm font-medium">Settings</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors">
            <span class="material-symbols-outlined">help</span>
            <span class="text-sm font-medium">Help Center</span>
        </a>
    </nav>

    <div class="p-4 border-t border-white/10">
        <div class="flex items-center gap-3 px-2">
            <div
                class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center overflow-hidden border border-white/10">
                <span class="material-symbols-outlined text-sm text-white">person</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-white/60 truncate">Administrator</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"
                    class="material-symbols-outlined text-white/60 text-sm cursor-pointer hover:text-white flex items-center">
                    logout
                </button>
            </form>
        </div>
    </div>
</aside>