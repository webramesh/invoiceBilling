<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-[#121617] dark:text-white tracking-tight leading-none">Dashboard</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2 font-medium">Infrastructure overview and business performance</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg px-4 py-2 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Current Plan</p>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-primary dark:text-white">{{ auth()->user()->saasPlan->name ?? 'None' }}</span>
                        @php
                            $isExpiringSoon = auth()->user()->plan_expires_at && auth()->user()->plan_expires_at->diffInDays(now()) < 7;
                        @endphp
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ $isExpiringSoon ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600' }}">
                            {{ auth()->user()->plan_expires_at ? auth()->user()->plan_expires_at->format('M d') : 'Lifetime' }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('pricing') }}" class="bg-primary hover:bg-primary-light text-white text-[11px] font-black uppercase tracking-widest px-4 py-2.5 rounded-lg transition-all shadow-md shadow-primary/20">
                    Upgrade
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 lg:mb-8">
        <div
            class="bg-white dark:bg-slate-900 p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">Total Clients</p>
            <div class="flex items-end justify-between mt-2">
                <p class="text-2xl font-extrabold text-primary dark:text-white">
                    {{ number_format($stats['total_clients']) }}
                </p>
                <span class="text-accent-emerald text-xs font-bold flex items-center">Active</span>
            </div>
        </div>
        <div
            class="bg-white dark:bg-slate-900 p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">Active
                Subscriptions</p>
            <div class="flex items-end justify-between mt-2">
                <p class="text-2xl font-extrabold text-accent-teal">{{ number_format($stats['active_subscriptions']) }}
                </p>
                <span class="text-accent-emerald text-xs font-bold flex items-center">Live</span>
            </div>
        </div>
        <div
            class="bg-white dark:bg-slate-900 p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">Revenue (Month)</p>
            <div class="flex items-end justify-between mt-2">
                <p class="text-2xl font-extrabold text-primary dark:text-white">Rs.
                    {{ number_format($stats['revenue_month'], 2) }}
                </p>
                <span class="text-accent-emerald text-xs font-bold flex items-center">Collected</span>
            </div>
        </div>
        <div
            class="bg-red-50 dark:bg-red-950/20 border-red-200 dark:border-red-900/50 p-4 sm:p-6 rounded-xl border shadow-sm">
            <p class="text-accent-red text-xs font-bold uppercase tracking-wider">Overdue Invoices</p>
            <div class="flex items-end justify-between mt-2">
                <p class="text-2xl font-extrabold text-accent-red">{{ $stats['overdue_invoices'] }}</p>
                <span class="bg-accent-red text-white text-[10px] px-2 py-0.5 rounded-full font-bold">URGENT</span>
            </div>
        </div>
    </div>

    <!-- Filters & Sync Row -->
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 lg:mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 w-full sm:w-auto">
            <span class="text-sm font-bold text-primary dark:text-slate-300">Quick Stats:</span>
            <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-lg w-full sm:w-auto">
                <div
                    class="px-4 py-1.5 bg-white dark:bg-slate-700 shadow-sm rounded-md text-accent-teal dark:text-white text-xs font-bold whitespace-nowrap">
                    Yearly: Rs. {{ number_format($stats['revenue_year'], 2) }}
                </div>
            </div>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <a href="{{ route('subscriptions.create') }}"
                class="flex-1 sm:flex-none bg-primary hover:bg-primary-light text-white text-xs font-bold py-2.5 px-4 sm:px-6 rounded-lg flex items-center justify-center gap-2 transition-colors shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-sm">add</span> New Subscription
            </a>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6 mb-6 lg:mb-8">
        <!-- Upcoming Renewals -->
        <div
            class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div
                class="px-4 sm:px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/20">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-accent-teal font-bold">event_upcoming</span>
                    <h3 class="text-sm sm:text-base font-bold text-primary dark:text-white">Upcoming Renewals</h3>
                </div>
                <span
                    class="text-[10px] font-black bg-teal-100 text-accent-teal dark:bg-teal-900/30 dark:text-teal-400 px-2 py-0.5 rounded uppercase">Next
                    30 Days</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Service
                            </th>
                            <th class="px-4 sm:px-6 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Expiry
                            </th>
                            <th
                                class="px-4 sm:px-6 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($upcomingRenewals as $sub)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-4 sm:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="size-8 bg-teal-50 dark:bg-teal-950/30 text-accent-teal rounded flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-lg">language</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-primary dark:text-white truncate">
                                                {{ $sub->service->name }}
                                            </p>
                                            <p class="text-[10px] text-slate-500 truncate">{{ $sub->client->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    <span
                                        class="text-xs font-bold {{ $sub->next_billing_date->isPast() ? 'text-accent-red bg-red-50' : 'text-slate-500 bg-slate-100' }} dark:bg-slate-800 px-2 py-1 rounded whitespace-nowrap">
                                        {{ $sub->next_billing_date->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-right">
                                    <a href="{{ route('subscriptions.show', $sub) }}"
                                        class="text-accent-teal hover:text-accent-teal-hover text-xs font-bold transition-all border border-accent-teal/20 hover:border-accent-teal px-3 py-1 rounded whitespace-nowrap">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 sm:px-6 py-8 text-center text-slate-400 text-sm italic">No
                                    upcoming
                                    renewals found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Invoices -->
        <div
            class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div
                class="px-4 sm:px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/20">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-accent-teal font-bold">receipt_long</span>
                    <h3 class="text-sm sm:text-base font-bold text-primary dark:text-white">Recent Invoices</h3>
                </div>
                <span
                    class="text-[10px] font-black bg-teal-100 text-accent-teal dark:bg-teal-900/30 dark:text-teal-400 px-2 py-0.5 rounded uppercase">Activity</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Invoice
                                / Client</th>
                            <th class="px-4 sm:px-6 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th class="px-4 sm:px-6 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th
                                class="px-4 sm:px-6 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($recentInvoices as $invoice)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-4 sm:px-6 py-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-primary dark:text-white truncate">
                                            {{ $invoice->invoice_number }}
                                        </p>
                                        <p class="text-[10px] text-slate-500 truncate">{{ $invoice->client->name }}</p>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    <span class="text-sm font-bold text-primary dark:text-white whitespace-nowrap">Rs.
                                        {{ number_format($invoice->total, 2) }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    <span
                                        class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ $invoice->status == 'paid' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }} whitespace-nowrap">
                                        {{ $invoice->status }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-right">
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                        class="text-accent-teal hover:text-accent-teal-hover text-xs font-bold transition-all whitespace-nowrap">Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 sm:px-6 py-8 text-center text-slate-400 text-sm italic">No
                                    recent
                                    invoices.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Audit Logs Section -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div
            class="px-4 sm:px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <h3 class="text-base font-bold text-primary dark:text-white">Recent System Activity</h3>
            <button
                class="text-accent-teal hover:text-accent-teal-hover text-xs font-bold flex items-center gap-1 transition-colors">
                Refresh Logs <span class="material-symbols-outlined text-sm">refresh</span>
            </button>
        </div>
        <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 dark:divide-slate-800">
            <div class="p-4 sm:p-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-accent-teal text-sm">alternate_email</span>
                    <p class="text-[10px] font-black text-accent-teal uppercase tracking-widest">Email Status</p>
                </div>
                <p class="text-sm font-medium leading-relaxed dark:text-slate-300">Mail server active. Waiting for next
                    batch.</p>
                <p class="text-[10px] text-slate-500 mt-2">Running</p>
            </div>
            <div class="p-4 sm:p-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-accent-teal text-sm">chat</span>
                    <p class="text-[10px] font-black text-accent-teal uppercase tracking-widest">WhatsApp Service</p>
                </div>
                <p class="text-sm font-medium leading-relaxed dark:text-slate-300">Node.js microservice connected and
                    ready.</p>
                <p class="text-[10px] text-slate-500 mt-2">Authenticated</p>
            </div>
            <div class="p-4 sm:p-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-accent-red text-sm">notifications_active</span>
                    <p class="text-[10px] font-black text-accent-red uppercase tracking-widest">Alerts</p>
                </div>
                <p class="text-sm font-medium leading-relaxed dark:text-slate-300">{{ $stats['overdue_invoices'] }}
                    invoices require immediate attention.</p>
                <p class="text-[10px] text-slate-500 mt-2">Critical</p>
            </div>
            <div class="p-4 sm:p-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-slate-400 text-sm">settings_suggest</span>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">System</p>
                </div>
                <p class="text-sm font-medium leading-relaxed dark:text-slate-300">Daily database job completed
                    successfully.</p>
                <p class="text-[10px] text-slate-500 mt-2">Completed</p>
            </div>
        </div>
    </div>
</x-app-layout>