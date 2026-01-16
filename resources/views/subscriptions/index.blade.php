<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-3xl font-black text-[#121617] dark:text-white tracking-tight">Subscription Management</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 font-medium">Monitor recurring revenue and service
                lifecycle</p>
        </div>
    </x-slot>

    <!-- Header Action Bar -->
    <div class="mb-8 flex justify-end">
        <a href="{{ route('subscriptions.create') }}"
            class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 shadow-xl shadow-primary/20 transition-all active:scale-95">
            <span class="material-symbols-outlined text-[20px]">add</span>
            New Subscription
        </a>
    </div>

    <div class="max-w-7xl mx-auto w-full space-y-8">
        <!-- Stats Row -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total MRR</span>
                    <div class="p-2 bg-green-50 dark:bg-green-900/20 text-green-600 rounded-lg">
                        <span class="material-symbols-outlined">trending_up</span>
                    </div>
                </div>
                <p class="text-3xl font-black text-[#121617] dark:text-white mb-1">Rs.
                    {{ number_format($stats['total_mrr']) }}</p>
                <p class="text-sm font-bold text-green-600 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">north_east</span> Active Revenue
                </p>
            </div>

            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Renewals this Month</span>
                    <div class="p-2 bg-primary/10 text-primary rounded-lg">
                        <span class="material-symbols-outlined">event_repeat</span>
                    </div>
                </div>
                <p class="text-3xl font-black text-[#121617] dark:text-white mb-1">{{ $stats['renewals_this_month'] }}
                </p>
                <p class="text-sm font-bold text-primary flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">schedule</span> Scheduled Tasks
                </p>
            </div>

            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Active Services</span>
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-lg">
                        <span class="material-symbols-outlined">dns</span>
                    </div>
                </div>
                <p class="text-3xl font-black text-[#121617] dark:text-white mb-1">{{ $stats['active_services_count'] }}
                </p>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                    Across {{ $stats['unique_clients_count'] }} unique clients
                </p>
            </div>
        </section>

        <!-- Filters and Search -->
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div
                class="flex gap-2 p-1 bg-gray-100 dark:bg-slate-800/50 rounded-xl w-full md:w-auto border border-gray-200 dark:border-slate-800">
                <a href="{{ route('subscriptions.index') }}"
                    class="px-5 py-2 text-xs font-bold {{ !request('cycle') ? 'bg-white dark:bg-slate-700 text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700' }} rounded-lg transition-all uppercase">All
                    Cycles</a>
                <a href="{{ route('subscriptions.index', ['cycle' => 'Monthly']) }}"
                    class="px-5 py-2 text-xs font-bold {{ request('cycle') === 'Monthly' ? 'bg-white dark:bg-slate-700 text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700' }} rounded-lg transition-all uppercase">Monthly</a>
                <a href="{{ route('subscriptions.index', ['cycle' => 'Yearly']) }}"
                    class="px-5 py-2 text-xs font-bold {{ request('cycle') === 'Yearly' ? 'bg-white dark:bg-slate-700 text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700' }} rounded-lg transition-all uppercase">Yearly</a>
            </div>

            <form action="{{ route('subscriptions.index') }}" method="GET" class="relative w-full md:w-80">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                <input name="search" value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-800 rounded-xl focus:ring-primary focus:border-primary text-sm shadow-sm"
                    placeholder="Search client or service..." type="text" />
            </form>
        </div>

        <!-- Subscriptions Table -->
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Client</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Service</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Cycle</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Next Billing
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-right">
                                Amount</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">
                                Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                        @forelse ($subscriptions as $sub)
                            <tr class="hover:bg-primary/5 dark:hover:bg-primary/5 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold text-[#121617] dark:text-white">{{ $sub->client->name }}</span>
                                        <span
                                            class="text-[11px] text-gray-400 font-bold uppercase tracking-tighter">{{ $sub->client->email }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary text-lg">dns</span>
                                        <span
                                            class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $sub->service->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span
                                        class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ $sub->billingCycle->name }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold {{ $sub->next_billing_date->isPast() ? 'text-accent-red' : 'text-gray-700 dark:text-gray-300' }}">
                                            {{ $sub->next_billing_date->format('M d, Y') }}
                                        </span>
                                        @if($sub->next_billing_date->isFuture())
                                            <span class="text-[10px] text-gray-400 font-bold">In
                                                {{ $sub->next_billing_date->diffForHumans(null, true) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm font-black text-[#121617] dark:text-white text-right">
                                    Rs. {{ number_format($sub->price, 2) }}
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span
                                        class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-widest {{ $sub->status === 'active' ? 'bg-green-500/10 text-green-600' : 'bg-red-500/10 text-accent-red' }}">
                                        {{ $sub->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end relative" x-data="{ open: false }">
                                        <button @click="open = !open" @click.away="open = false"
                                            class="p-2 text-gray-400 hover:text-primary transition-colors focus:outline-none">
                                            <span class="material-symbols-outlined text-[24px]">more_vert</span>
                                        </button>

                                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                            class="absolute right-0 mt-10 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-gray-100 dark:border-slate-700 z-50 overflow-hidden"
                                            style="display: none;">
                                            <div class="py-1">
                                                <a href="{{ route('subscriptions.edit', $sub) }}"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700">
                                                    <span class="material-symbols-outlined text-lg">edit</span> Edit Service
                                                </a>
                                                <form action="{{ route('subscriptions.destroy', $sub) }}" method="POST"
                                                    class="w-full" onsubmit="return confirm('Archive this service?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10">
                                                        <span class="material-symbols-outlined text-lg">cancel</span> Cancel
                                                        Service
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">No active subscriptions
                                    found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($subscriptions->hasPages())
                <div
                    class="px-6 py-4 bg-gray-50 dark:bg-slate-800/30 border-t border-gray-100 dark:border-slate-800 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Showing
                        {{ $subscriptions->firstItem() }}-{{ $subscriptions->lastItem() }} of
                        {{ $subscriptions->total() }}</span>
                    <div class="flex gap-2">
                        {{ $subscriptions->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>