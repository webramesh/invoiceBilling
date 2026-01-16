<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-3xl font-black text-[#121617] dark:text-white tracking-tight">Clients</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 font-medium">Manage and monitor customer service accounts</p>
        </div>
    </x-slot>

    <!-- Action Bar Below Header -->
    <div class="mb-6 flex justify-end">
        <a href="{{ route('clients.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-lg font-bold text-sm hover:bg-primary/90 shadow-xl shadow-primary/20 transition-all active:scale-95">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            Add New Client
        </a>
    </div>

    <!-- Dashboard Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
        <!-- Main Table Section -->
        <div class="xl:col-span-3 space-y-6">
            @if (session('success'))
                <div
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4 rounded-xl text-green-700 dark:text-green-400 text-sm font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search and Filter Utility Bar -->
            <div
                class="bg-white dark:bg-background-dark p-4 rounded-xl border border-gray-200 dark:border-white/10 flex flex-wrap gap-4 items-center">
                <form action="{{ route('clients.index') }}" method="GET"
                    class="flex-1 flex flex-wrap gap-4 items-center">
                    <div class="relative flex-1 min-w-[300px]">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input name="search" value="{{ request('search') }}"
                            class="w-full pl-11 pr-4 py-2.5 bg-background-light dark:bg-white/5 border-none rounded-lg focus:ring-2 focus:ring-primary text-sm transition-all"
                            placeholder="Search by name, email, or domain..." type="text" />
                    </div>
                    <div class="flex gap-2">
                        <select name="status" onchange="this.form.submit()"
                            class="bg-background-light dark:bg-white/5 border-none rounded-lg text-sm font-semibold focus:ring-2 focus:ring-primary py-2.5 px-4 pr-10 appearance-none">
                            <option value="">Status: All</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                        <a href="{{ route('clients.index') }}"
                            class="p-2.5 text-gray-400 hover:text-primary transition-colors flex items-center justify-center">
                            <span class="material-symbols-outlined">refresh</span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div
                class="bg-white dark:bg-background-dark border border-gray-200 dark:border-white/10 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-white/10 bg-gray-50/50 dark:bg-white/5">
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Name
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Email
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">
                                    Active Services</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Invoiced
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                            @forelse ($clients as $client)
                                <tr class="hover:bg-background-light dark:hover:bg-white/5 transition-colors group">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="size-9 bg-primary/10 text-primary rounded-full flex items-center justify-center font-bold text-xs">
                                                {{ strtoupper(substr($client->name, 0, 1)) }}{{ str_contains($client->name, ' ') ? strtoupper(substr(explode(' ', $client->name)[1], 0, 1)) : '' }}
                                            </div>
                                            <span
                                                class="font-bold text-[#121617] dark:text-white">{{ $client->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-gray-500 dark:text-gray-400">{{ $client->email }}</td>
                                    <td class="px-6 py-5 text-center">
                                        <div class="flex flex-wrap justify-center gap-1.5">
                                            <span
                                                class="px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-bold rounded uppercase">
                                                {{ $client->subscriptions_count }} ACTIVE
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 font-bold text-[#121617] dark:text-white">Rs.
                                        {{ number_format($client->invoices_sum_total ?: 0, 2) }}</td>
                                    <td class="px-6 py-5 text-center">
                                        <span
                                            class="inline-flex items-center px-3 py-1 {{ $client->status === 'active' ? 'bg-green-500/10 text-green-600 dark:text-green-400' : 'bg-gray-500/10 text-gray-500' }} text-xs font-bold rounded-full uppercase">
                                            {{ $client->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ route('clients.show', $client) }}"
                                                class="p-2 text-gray-400 hover:text-primary transition-colors">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </a>
                                            <a href="{{ route('clients.edit', $client) }}"
                                                class="p-2 text-gray-400 hover:text-primary transition-colors">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                                class="inline" onsubmit="return confirm('Archive user?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">No clients found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if($clients->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-white/10 flex items-center justify-between">
                        <p class="text-sm text-gray-500 font-medium">Showing {{ $clients->firstItem() }} to
                            {{ $clients->lastItem() }} of {{ $clients->total() }} clients</p>
                        <div class="flex gap-2">
                            @if($clients->onFirstPage())
                                <span
                                    class="px-4 py-2 bg-background-light dark:bg-white/5 rounded-lg text-sm font-bold opacity-50 cursor-not-allowed text-gray-400">Previous</span>
                            @else
                                <a href="{{ $clients->previousPageUrl() }}"
                                    class="px-4 py-2 bg-background-light dark:bg-white/5 rounded-lg text-sm font-bold hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">Previous</a>
                            @endif

                            @if($clients->hasMorePages())
                                <a href="{{ $clients->nextPageUrl() }}"
                                    class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary/90 transition-colors">Next</a>
                            @else
                                <span
                                    class="px-4 py-2 bg-primary/50 text-white rounded-lg text-sm font-bold opacity-50 cursor-not-allowed">Next</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Sidebar Stats Panel -->
        <div class="xl:col-span-1 space-y-6">
            <div class="bg-primary p-6 rounded-2xl shadow-xl shadow-primary/20 relative overflow-hidden group">
                <!-- Background Pattern Decor -->
                <div
                    class="absolute -right-10 -top-10 size-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-700">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="size-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-white">groups_2</span>
                        </div>
                        <span
                            class="text-[10px] font-black uppercase text-white/60 tracking-widest bg-white/10 px-2 py-1 rounded">Real-time</span>
                    </div>
                    <p class="text-white/80 text-sm font-medium">Total Active Clients</p>
                    <h3 class="text-white text-5xl font-black mt-1">{{ number_format($stats['total_active']) }}</h3>
                    <div class="mt-6 flex items-center gap-2 text-white/90">
                        <span class="material-symbols-outlined text-green-300">trending_up</span>
                        <span class="text-xs font-bold tracking-tight"><span
                                class="text-green-300">+{{ $stats['new_this_month'] }}</span> new this month</span>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-background-dark p-6 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">
                <h4 class="text-[#121617] dark:text-white text-sm font-bold mb-4 uppercase tracking-widest">Growth
                    Metrics</h4>
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-xs font-semibold text-gray-500 uppercase">Retention Rate</span>
                            <span class="text-xl font-black text-primary">{{ $stats['retention_rate'] }}%</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full" style="width: {{ $stats['retention_rate'] }}%">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-xs font-semibold text-gray-500 uppercase">New Acquisitions</span>
                            <span class="text-xl font-black text-primary">{{ $stats['new_this_month'] }}</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full bg-primary w-[45%] rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="p-6 rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/10 flex flex-col items-center text-center">
                <div
                    class="size-12 bg-background-light dark:bg-white/5 rounded-full flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-gray-400">help_outline</span>
                </div>
                <h5 class="text-sm font-bold text-[#121617] dark:text-white">Need help managing clients?</h5>
                <p class="text-xs text-gray-500 mt-1 mb-4">Check our knowledge base for platform tutorials and FAQs.</p>
                <a class="text-xs font-bold text-primary hover:underline flex items-center gap-1" href="#">
                    Visit Help Center <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>