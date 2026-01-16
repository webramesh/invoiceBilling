<aside class="w-64 bg-primary dark:bg-primary/90 flex flex-col shrink-0 fixed inset-y-0 z-50">
    <div class="p-6">
        <div class="flex items-center gap-3 mb-10">
            <div class="size-10 bg-white rounded-xl flex items-center justify-center text-primary">
                <span class="material-symbols-outlined font-bold">cloud_sync</span>
            </div>
            <div class="flex flex-col">
                <h1 class="text-white text-lg font-bold leading-none">Bill Easy</h1>
                <p class="text-white/70 text-xs font-medium uppercase tracking-wider mt-1">Infrastructure</p>
            </div>
        </div>
        <nav class="flex flex-col gap-1">
            <a class="flex items-center gap-3 px-4 py-3 transition-colors rounded-lg {{ request()->routeIs('dashboard') ? 'text-white active-nav' : 'text-white/80 hover:text-white' }}"
                href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined font-normal">dashboard</span>
                <span
                    class="text-sm {{ request()->routeIs('dashboard') ? 'font-semibold' : 'font-medium' }}">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 transition-colors rounded-lg {{ request()->routeIs('clients.*') ? 'text-white active-nav' : 'text-white/80 hover:text-white' }}"
                href="{{ route('clients.index') }}">
                <span class="material-symbols-outlined font-normal">group</span>
                <span
                    class="text-sm {{ request()->routeIs('clients.*') ? 'font-semibold' : 'font-medium' }}">Clients</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 transition-colors rounded-lg {{ request()->routeIs('subscriptions.*') ? 'text-white active-nav' : 'text-white/80 hover:text-white' }}"
                href="{{ route('subscriptions.index') }}">
                <span class="material-symbols-outlined font-normal">credit_card</span>
                <span
                    class="text-sm {{ request()->routeIs('subscriptions.*') ? 'font-semibold' : 'font-medium' }}">Subscriptions</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 transition-colors rounded-lg {{ request()->routeIs('services.*') ? 'text-white active-nav' : 'text-white/80 hover:text-white' }}"
                href="{{ route('services.index') }}">
                <span class="material-symbols-outlined font-normal">dns</span>
                <span
                    class="text-sm {{ request()->routeIs('services.*') ? 'font-semibold' : 'font-medium' }}">Services</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 transition-colors rounded-lg {{ request()->routeIs('invoices.*') ? 'text-white active-nav' : 'text-white/80 hover:text-white' }}"
                href="{{ route('invoices.index') }}">
                <span class="material-symbols-outlined font-normal">description</span>
                <span
                    class="text-sm {{ request()->routeIs('invoices.*') ? 'font-semibold' : 'font-medium' }}">Invoices</span>
            </a>
        </nav>
    </div>
    <div class="mt-auto p-6 border-t border-white/10">
        <div class="w-full flex items-center gap-3 p-2 bg-white/10 rounded-lg group">
            <div
                class="size-8 rounded-full overflow-hidden border-2 border-white/20 flex items-center justify-center bg-white/10">
                <span class="material-symbols-outlined text-sm text-white">person</span>
            </div>
            <div class="text-left overflow-hidden">
                <p class="text-white text-xs font-bold truncate">{{ Auth::user()->name }}</p>
                <p class="text-white/60 text-[10px]">Super Admin</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="ml-auto">
                @csrf
                <button type="submit"
                    class="material-symbols-outlined text-white/40 group-hover:text-white transition-colors">logout</button>
            </form>
        </div>
    </div>
</aside>