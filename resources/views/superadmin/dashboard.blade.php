<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-[#121617] dark:text-white tracking-tight leading-none">Platform Overview</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2 font-medium">SAAS Master Console • Global Statistics</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg px-4 py-2 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Status</p>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-primary dark:text-white">SUPER ADMIN MODE</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase bg-emerald-100 text-emerald-600">
                            LIVE
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 lg:mb-8">
        <div class="bg-white dark:bg-slate-900 p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">Total Tenants</p>
            <div class="flex items-end justify-between mt-2">
                <p class="text-2xl font-extrabold text-primary dark:text-white">
                    {{ number_format($stats['total_tenants']) }}
                </p>
                <span class="text-emerald-500 text-[10px] font-bold flex items-center gap-1 uppercase">
                    <span class="size-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Growth Active
                </span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">Active Subscriptions</p>
            <div class="flex items-end justify-between mt-2">
                <p class="text-2xl font-extrabold text-primary dark:text-white">
                    {{ number_format($stats['active_tenants']) }}
                </p>
                <span class="text-emerald-500 text-[10px] font-bold uppercase">Paying / Trialing</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">Platform MRR</p>
            <div class="flex items-end justify-between mt-2">
                <p class="text-2xl font-extrabold text-primary dark:text-white">
                    Rs. {{ number_format($stats['monthly_recurring_revenue'], 2) }}
                </p>
                <span class="text-slate-400 text-[10px] font-bold uppercase">Gross Projected</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">Invoices Processed</p>
            <div class="flex items-end justify-between mt-2">
                <p class="text-2xl font-extrabold text-[#121617] dark:text-white">
                    {{ number_format($stats['total_platform_invoices']) }}
                </p>
                <span class="text-slate-400 text-[10px] font-bold uppercase italic">Platform Total</span>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6 mb-6 lg:mb-8">
        <!-- Recent Signups -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/20">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary font-bold">rocket_launch</span>
                    <h3 class="text-sm sm:text-base font-bold text-primary dark:text-white">Recent Signups</h3>
                </div>
                <a href="#" class="text-[10px] font-black uppercase tracking-widest text-primary hover:text-primary-light transition-colors">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 dark:bg-slate-800/30">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Business Owner</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Plan</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 transition-all">
                        @foreach($recentTenants as $tenant)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="size-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                                            {{ substr($tenant->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800 dark:text-white leading-none">{{ $tenant->name }}</p>
                                            <p class="text-[10px] text-slate-400 mt-1">{{ $tenant->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 uppercase">
                                        {{ $tenant->saasPlan->name ?? 'None' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($tenant->plan_expires_at && $tenant->plan_expires_at->isFuture())
                                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                                            <span class="size-1.5 bg-emerald-500 rounded-full"></span> ACTIVE
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold text-red-500 uppercase">Expired</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-xs font-semibold text-slate-500">{{ $tenant->created_at->format('M d, Y') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Plan Distribution -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/20">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary font-bold">pie_chart</span>
                    <h3 class="text-sm sm:text-base font-bold text-primary dark:text-white">Plan Distribution</h3>
                </div>
            </div>
            <div class="p-6 flex-1">
                <div class="space-y-6">
                    @foreach($plansDistribution as $plan)
                        <div>
                            <div class="flex justify-between text-[11px] font-black uppercase tracking-widest text-slate-500 mb-2">
                                <span>{{ $plan->name }}</span>
                                <span class="text-primary">{{ $plan->users_count }} users</span>
                            </div>
                            <div class="h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                @php
                                    $percentage = $stats['total_tenants'] > 0 ? ($plan->users_count / $stats['total_tenants']) * 100 : 0;
                                @endphp
                                <div class="h-full bg-primary transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-auto pt-8">
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-100 dark:border-slate-800">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 leading-none">Global Control</p>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="bg-white dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 text-slate-800 dark:text-white text-[10px] font-black uppercase py-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Manage Plans</button>
                            <button class="bg-primary text-white text-[10px] font-black uppercase py-2.5 rounded-lg hover:opacity-90 transition-all shadow-lg shadow-primary/20">Platform Settings</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
