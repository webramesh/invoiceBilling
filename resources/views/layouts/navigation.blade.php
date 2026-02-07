<!-- Mobile Backdrop -->
<div x-show="sidebarOpen" 
    @click="sidebarOpen = false" 
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" 
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" 
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" 
    class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 lg:hidden"
    style="display: none;">
</div>

<!-- Sidebar -->
<aside x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    :class="sidebarCollapsed ? 'lg:w-20' : 'lg:w-64'"
    class="w-64 bg-primary text-white flex flex-col fixed h-full z-[60] transition-all duration-300 ease-in-out shadow-2xl lg:translate-x-0"
    :style="{ 
        backgroundColor: 'rgb(36,146,168)',
        transform: !sidebarOpen && window.innerWidth < 1024 ? 'translateX(-100%)' : ''
    }">

    <!-- Header -->
    <div class="p-6 flex items-center justify-between gap-3 relative" :class="sidebarCollapsed ? 'lg:justify-center lg:p-4' : ''">
        <div class="flex items-center gap-3 overflow-hidden" :class="sidebarCollapsed ? 'lg:justify-center' : ''">
            <div class="bg-white/20 p-2 rounded-lg flex-shrink-0 transition-all" :class="sidebarCollapsed ? 'lg:p-2.5' : ''">
                <span class="material-symbols-outlined text-white text-2xl" :class="sidebarCollapsed ? 'lg:text-xl' : ''">
                    {{ request()->routeIs('superadmin.*') ? 'shield_person' : 'rocket_launch' }}
                </span>
            </div>
            <div x-show="!sidebarCollapsed" 
                x-transition:enter="transition ease-in duration-200 delay-100"
                x-transition:enter-start="opacity-0 -translate-x-4" 
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-out duration-150" 
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 -translate-x-4"
                class="hidden lg:block">
                <h1 class="text-lg font-bold leading-none whitespace-nowrap">{{ request()->routeIs('superadmin.*') ? 'Master Hub' : 'Bill Easy' }}</h1>
                <p class="text-white/70 text-[10px] uppercase tracking-widest mt-1 whitespace-nowrap">{{ request()->routeIs('superadmin.*') ? 'Platform Admin' : 'Infrastructure' }}</p>
            </div>
            <!-- Mobile: Always show when sidebar is open -->
            <div class="lg:hidden">
                <h1 class="text-lg font-bold leading-none whitespace-nowrap">{{ request()->routeIs('superadmin.*') ? 'Master Hub' : 'Bill Easy' }}</h1>
                <p class="text-white/70 text-[10px] uppercase tracking-widest mt-1 whitespace-nowrap">{{ request()->routeIs('superadmin.*') ? 'Platform Admin' : 'Infrastructure' }}</p>
            </div>
        </div>

        <!-- Close Button (Mobile Only) -->
        <button @click="sidebarOpen = false"
            class="lg:hidden p-1 text-white/60 hover:text-white transition-colors flex-shrink-0">
            <span class="material-symbols-outlined">close</span>
        </button>

        <!-- Collapse Toggle Button (Desktop Only - Only show when sidebar is visible) -->
        <button @click="toggleSidebar()"
            x-show="sidebarOpen || window.innerWidth >= 1024"
            class="hidden lg:flex absolute -right-3 top-1/2 -translate-y-1/2 w-7 h-7 bg-white text-primary rounded-full items-center justify-center hover:scale-110 transition-all shadow-lg hover:shadow-xl z-10">
            <span class="material-symbols-outlined text-base transition-transform duration-300" 
                :class="sidebarCollapsed ? 'rotate-180' : ''">
                chevron_left
            </span>
        </button>
    </div>

    <nav class="flex-1 mt-6 px-3 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-white/20 scrollbar-track-transparent">
        @if(Auth::user()->is_admin)
            <!-- Platform Toggle for SuperAdmin -->
            <div class="mb-4">
                <a href="{{ request()->routeIs('superadmin.*') ? route('dashboard') : route('superadmin.dashboard') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 hover:bg-white/20 transition-all border border-white/10 group relative shadow-inner">
                    <span class="material-symbols-outlined flex-shrink-0 text-xl text-amber-400 font-fill">
                        {{ request()->routeIs('superadmin.*') ? 'storefront' : 'shield_person' }}
                    </span>
                    <span x-show="!sidebarCollapsed" class="text-[10px] font-black uppercase tracking-tighter hidden lg:block leading-none">
                        {{ request()->routeIs('superadmin.*') ? 'Switch to Business App' : 'Access platform console' }}
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-tighter lg:hidden leading-none">
                        {{ request()->routeIs('superadmin.*') ? 'Switch to Business App' : 'Access platform console' }}
                    </span>
                </a>
            </div>
            <div class="h-px bg-white/5 mb-4 mx-2"></div>
        @endif

        @if(request()->routeIs('superadmin.*'))
            <!-- SuperAdmin Menu Items -->
            <a href="{{ route('superadmin.dashboard') }}" 
                :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('superadmin.dashboard') ? 'sidebar-item-active' : 'hover:bg-white/10' }} group relative"
                x-data="{ tooltip: false }"
                @mouseenter="tooltip = sidebarCollapsed" 
                @mouseleave="tooltip = false">
                <span class="material-symbols-outlined flex-shrink-0 text-xl">dashboard</span>
                <span x-show="!sidebarCollapsed" 
                    x-transition:enter="transition ease-in duration-200 delay-75"
                    x-transition:enter-start="opacity-0 translate-x-2" 
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-out duration-100" 
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="text-sm font-medium whitespace-nowrap hidden lg:block">Dashboard</span>
                <span class="text-sm font-medium whitespace-nowrap lg:hidden">Dashboard</span>
                <div x-show="tooltip && sidebarCollapsed" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-x-2" 
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="hidden lg:block absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-xs font-medium rounded-lg shadow-lg whitespace-nowrap z-50"
                    style="display: none;">
                    Dashboard
                </div>
            </a>

            <!-- Business Owners -->
            <a href="{{ route('superadmin.business-owners.index') }}" 
                :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('superadmin.business-owners.*') ? 'sidebar-item-active' : 'hover:bg-white/10' }} group relative"
                x-data="{ tooltip: false }"
                @mouseenter="tooltip = sidebarCollapsed" 
                @mouseleave="tooltip = false">
                <span class="material-symbols-outlined flex-shrink-0 text-xl">storefront</span>
                <span x-show="!sidebarCollapsed" 
                    x-transition:enter="transition ease-in duration-200 delay-75"
                    x-transition:enter-start="opacity-0 translate-x-2" 
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-out duration-100" 
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="text-sm font-medium whitespace-nowrap hidden lg:block">Business Owners</span>
                <span class="text-sm font-medium whitespace-nowrap lg:hidden">Business Owners</span>
                <div x-show="tooltip && sidebarCollapsed" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-x-2" 
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="hidden lg:block absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-xs font-medium rounded-lg shadow-lg whitespace-nowrap z-50"
                    style="display: none;">
                    Business Owners
                </div>
            </a>
        @else
            <!-- Business Owner Menu Items -->
            <a href="{{ route('dashboard') }}" 
                :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('dashboard') ? 'sidebar-item-active' : 'hover:bg-white/10' }} group relative"
                x-data="{ tooltip: false }"
                @mouseenter="tooltip = sidebarCollapsed" 
                @mouseleave="tooltip = false">
                <span class="material-symbols-outlined flex-shrink-0 text-xl">dashboard</span>
                <!-- Desktop: Show/Hide based on collapsed -->
                <span x-show="!sidebarCollapsed" 
                    x-transition:enter="transition ease-in duration-200 delay-75"
                    x-transition:enter-start="opacity-0 translate-x-2" 
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-out duration-100" 
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="text-sm font-medium whitespace-nowrap hidden lg:block">Dashboard</span>
                <!-- Mobile: Always show -->
                <span class="text-sm font-medium whitespace-nowrap lg:hidden">Dashboard</span>
                <!-- Tooltip -->
                <div x-show="tooltip && sidebarCollapsed" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-x-2" 
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="hidden lg:block absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-xs font-medium rounded-lg shadow-lg whitespace-nowrap z-50"
                    style="display: none;">
                    Dashboard
                </div>
            </a>

            <!-- Clients -->
        <a href="{{ route('clients.index') }}" 
            :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('clients.*') ? 'sidebar-item-active' : 'hover:bg-white/10' }} group relative"
            x-data="{ tooltip: false }"
            @mouseenter="tooltip = sidebarCollapsed" 
            @mouseleave="tooltip = false">
            <span class="material-symbols-outlined flex-shrink-0 text-xl">group</span>
            <span x-show="!sidebarCollapsed" 
                x-transition:enter="transition ease-in duration-200 delay-75"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-out duration-100" 
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="text-sm font-medium whitespace-nowrap hidden lg:block">Clients</span>
            <span class="text-sm font-medium whitespace-nowrap lg:hidden">Clients</span>
            <div x-show="tooltip && sidebarCollapsed" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                class="hidden lg:block absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-xs font-medium rounded-lg shadow-lg whitespace-nowrap z-50"
                style="display: none;">
                Clients
            </div>
        </a>

        <!-- Subscriptions -->
        <a href="{{ route('subscriptions.index') }}" 
            :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('subscriptions.*') ? 'sidebar-item-active' : 'hover:bg-white/10' }} group relative"
            x-data="{ tooltip: false }"
            @mouseenter="tooltip = sidebarCollapsed" 
            @mouseleave="tooltip = false">
            <span class="material-symbols-outlined flex-shrink-0 text-xl">receipt_long</span>
            <span x-show="!sidebarCollapsed" 
                x-transition:enter="transition ease-in duration-200 delay-75"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-out duration-100" 
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="text-sm font-medium whitespace-nowrap hidden lg:block">Subscriptions</span>
            <span class="text-sm font-medium whitespace-nowrap lg:hidden">Subscriptions</span>
            <div x-show="tooltip && sidebarCollapsed" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                class="hidden lg:block absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-xs font-medium rounded-lg shadow-lg whitespace-nowrap z-50"
                style="display: none;">
                Subscriptions
            </div>
        </a>

        <!-- Services -->
        <a href="{{ route('services.index') }}" 
            :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('services.*') ? 'sidebar-item-active' : 'hover:bg-white/10' }} group relative"
            x-data="{ tooltip: false }"
            @mouseenter="tooltip = sidebarCollapsed" 
            @mouseleave="tooltip = false">
            <span class="material-symbols-outlined flex-shrink-0 text-xl">dns</span>
            <span x-show="!sidebarCollapsed" 
                x-transition:enter="transition ease-in duration-200 delay-75"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-out duration-100" 
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="text-sm font-medium whitespace-nowrap hidden lg:block">Services</span>
            <span class="text-sm font-medium whitespace-nowrap lg:hidden">Services</span>
            <div x-show="tooltip && sidebarCollapsed" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                class="hidden lg:block absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-xs font-medium rounded-lg shadow-lg whitespace-nowrap z-50"
                style="display: none;">
                Services
            </div>
        </a>

        <!-- Billing -->
        <a href="{{ route('invoices.index') }}" 
            :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('invoices.*') ? 'sidebar-item-active' : 'hover:bg-white/10' }} group relative"
            x-data="{ tooltip: false }"
            @mouseenter="tooltip = sidebarCollapsed" 
            @mouseleave="tooltip = false">
            <span class="material-symbols-outlined flex-shrink-0 text-xl">payments</span>
            <span x-show="!sidebarCollapsed" 
                x-transition:enter="transition ease-in duration-200 delay-75"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-out duration-100" 
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="text-sm font-medium whitespace-nowrap hidden lg:block">Billing</span>
            <span class="text-sm font-medium whitespace-nowrap lg:hidden">Billing</span>
            <div x-show="tooltip && sidebarCollapsed" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                class="hidden lg:block absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-xs font-medium rounded-lg shadow-lg whitespace-nowrap z-50"
                style="display: none;">
                Billing
            </div>
        </a>

        @endif

        <!-- System Section Header -->
        <div x-show="!sidebarCollapsed" class="pt-4 pb-2 px-4 hidden lg:block">
            <p class="text-white/40 text-[10px] uppercase font-bold tracking-wider">System</p>
        </div>
        <div class="pt-4 pb-2 px-4 lg:hidden">
            <p class="text-white/40 text-[10px] uppercase font-bold tracking-wider">System</p>
        </div>
        <div x-show="sidebarCollapsed" class="pt-4 pb-2 hidden lg:block" style="display: none;">
            <div class="h-px bg-white/20 mx-4"></div>
        </div>

        <!-- Account -->
        <a href="{{ route('profile.edit') }}" 
            :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('profile.*') ? 'sidebar-item-active' : 'hover:bg-white/10' }} group relative"
            x-data="{ tooltip: false }"
            @mouseenter="tooltip = sidebarCollapsed" 
            @mouseleave="tooltip = false">
            <span class="material-symbols-outlined flex-shrink-0 text-xl">person</span>
            <span x-show="!sidebarCollapsed" 
                x-transition:enter="transition ease-in duration-200 delay-75"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-out duration-100" 
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="text-sm font-medium whitespace-nowrap hidden lg:block">Account</span>
            <span class="text-sm font-medium whitespace-nowrap lg:hidden">Account</span>
            <div x-show="tooltip && sidebarCollapsed" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                class="hidden lg:block absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-xs font-medium rounded-lg shadow-lg whitespace-nowrap z-50"
                style="display: none;">
                Account
            </div>
        </a>

        <!-- System Settings -->
        <a href="{{ route('settings.index') }}" 
            :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('settings.*') ? 'sidebar-item-active' : 'hover:bg-white/10' }} group relative"
            x-data="{ tooltip: false }"
            @mouseenter="tooltip = sidebarCollapsed" 
            @mouseleave="tooltip = false">
            <span class="material-symbols-outlined flex-shrink-0 text-xl">tune</span>
            <span x-show="!sidebarCollapsed" 
                x-transition:enter="transition ease-in duration-200 delay-75"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-out duration-100" 
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" 
                class="text-sm font-medium whitespace-nowrap hidden lg:block">System Settings</span>
            <span class="text-sm font-medium whitespace-nowrap lg:hidden">System Settings</span>
            <div x-show="tooltip && sidebarCollapsed" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                class="hidden lg:block absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-xs font-medium rounded-lg shadow-lg whitespace-nowrap z-50"
                style="display: none;">
                System Settings
            </div>
        </a>

        <!-- Help Center -->
        <a href="#" 
            :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-all group relative"
            x-data="{ tooltip: false }"
            @mouseenter="tooltip = sidebarCollapsed" 
            @mouseleave="tooltip = false">
            <span class="material-symbols-outlined flex-shrink-0 text-xl">help</span>
            <span x-show="!sidebarCollapsed" 
                x-transition:enter="transition ease-in duration-200 delay-75"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-out duration-100" 
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" 
                class="text-sm font-medium whitespace-nowrap hidden lg:block">Help Center</span>
            <span class="text-sm font-medium whitespace-nowrap lg:hidden">Help Center</span>
            <div x-show="tooltip && sidebarCollapsed" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                class="hidden lg:block absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-xs font-medium rounded-lg shadow-lg whitespace-nowrap z-50"
                style="display: none;">
                Help Center
            </div>
        </a>
    </nav>

    <!-- User Section -->
    <div class="p-4 border-t border-white/10">
        <div class="flex items-center gap-3 px-2" :class="sidebarCollapsed ? 'lg:justify-center' : ''">
            <div
                class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center overflow-hidden border border-white/10 flex-shrink-0">
                <span class="material-symbols-outlined text-sm text-white">person</span>
            </div>
            <!-- Desktop: Show/Hide based on collapsed -->
            <div x-show="!sidebarCollapsed" 
                x-transition:enter="transition ease-in duration-200 delay-75"
                x-transition:enter-start="opacity-0 translate-x-2" 
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-out duration-100" 
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" 
                class="flex-1 min-w-0 hidden lg:block">
                <p class="text-xs font-semibold truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-white/60 truncate">{{ Auth::user()->is_admin ? 'SaaS Master' : (Auth::user()->saasPlan->name ?? 'Subscribed') }}</p>
            </div>
            <!-- Mobile: Always show -->
            <div class="flex-1 min-w-0 lg:hidden">
                <p class="text-xs font-semibold truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-white/60 truncate">{{ Auth::user()->is_admin ? 'SaaS Master' : (Auth::user()->saasPlan->name ?? 'Subscribed') }}</p>
            </div>
            <!-- Desktop: Show/Hide based on collapsed -->
            <form method="POST" action="{{ route('logout') }}" class="inline hidden lg:block" 
                x-show="!sidebarCollapsed"
                x-transition:enter="transition ease-in duration-200 delay-75"
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-out duration-100" 
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                @csrf
                <button type="submit" 
                    class="material-symbols-outlined text-white/60 text-sm cursor-pointer hover:text-white flex items-center transition-colors">
                    logout
                </button>
            </form>
            <!-- Mobile: Always show -->
            <form method="POST" action="{{ route('logout') }}" class="inline lg:hidden">
                @csrf
                <button type="submit" 
                    class="material-symbols-outlined text-white/60 text-sm cursor-pointer hover:text-white flex items-center transition-colors">
                    logout
                </button>
            </form>
        </div>
    </div>
</aside>
