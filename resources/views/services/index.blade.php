<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-black text-[#121617] dark:text-white tracking-tight">Service Catalog</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Manage and monitor your business service offerings</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('service-categories.index') }}"
                    class="bg-white dark:bg-slate-800 border border-[#dce3e4] dark:border-slate-800 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-lg font-bold text-sm transition-all flex items-center gap-2 hover:bg-gray-50 dark:hover:bg-slate-700">
                    <span class="material-symbols-outlined text-[20px]">category</span>
                    Categories
                </a>
                <a href="{{ route('services.create') }}"
                    class="bg-primary hover:bg-teal-dark text-white px-6 py-2.5 rounded-lg font-bold text-sm transition-all flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Add New Service
                </a>
            </div>
        </div>

        <!-- Search & Filter Bar -->
        <div
            class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-800 rounded-xl p-4 mb-6 shadow-sm">
            <form action="{{ route('services.index') }}" method="GET"
                class="relative flex items-center w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 text-gray-400">search</span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full bg-background-light dark:bg-gray-800 border-none rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/50 transition-all placeholder:text-gray-400 dark:text-white"
                    placeholder="Search services..." />
            </form>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined text-green-500">check_circle</span>
                <p class="text-sm font-bold text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined text-red-500">error</span>
                <p class="text-sm font-bold text-red-700 dark:text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Data Table -->
        <div
            class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Service Name</th>
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Category</th>
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-right">
                                Default Price</th>
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">
                                Active Clients</th>
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($services as $service)
                            <tr class="hover:bg-primary/5 dark:hover:bg-primary/10 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-9 w-9 bg-primary/10 rounded-lg flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                            @php
                                                $icon = 'dns';
                                                $catName = strtolower($service->category->name);
                                                if (str_contains($catName, 'hosting'))
                                                    $icon = 'dns';
                                                elseif (str_contains($catName, 'domain'))
                                                    $icon = 'language';
                                                elseif (str_contains($catName, 'email'))
                                                    $icon = 'alternate_email';
                                                elseif (str_contains($catName, 'security') || str_contains($catName, 'ssl'))
                                                    $icon = 'security';
                                            @endphp
                                            <span class="material-symbols-outlined">{{ $icon }}</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-[#121617] dark:text-white">{{ $service->name }}
                                            </p>
                                            <p class="text-xs text-gray-400 font-medium truncate max-w-[200px]">
                                                {{ $service->description ?? 'No description' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary/10 text-primary uppercase">
                                        {{ $service->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right font-medium text-sm text-gray-600 dark:text-gray-300">
                                    Rs. {{ number_format($service->base_price) }}
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span
                                        class="text-sm font-bold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-full">
                                        {{ $service->active_clients_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right space-x-2">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('services.edit', $service) }}"
                                            class="p-2 text-primary hover:bg-primary/20 rounded-lg transition-colors"
                                            title="Edit Service">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form action="{{ route('services.destroy', $service) }}" method="POST"
                                            class="inline-block" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Delete Service">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <span class="material-symbols-outlined text-4xl">inventory_2</span>
                                        <p class="font-bold">No services found in the catalog</p>
                                        <a href="{{ route('services.create') }}"
                                            class="text-primary hover:underline text-sm uppercase tracking-widest font-black">Add
                                            Your First Service</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($services->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                    {{ $services->links() }}
                </div>
            @endif
        </div>

        <!-- Quick Stats / Insight -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="p-6 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-800 rounded-xl shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Services</p>
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-black text-primary leading-none">{{ $stats['total_services'] }}</span>
                    @if($stats['services_this_month'] > 0)
                        <span class="text-xs text-green-500 font-bold mb-1">+{{ $stats['services_this_month'] }} this
                            month</span>
                    @endif
                </div>
            </div>
            <div
                class="p-6 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-800 rounded-xl shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Active Subscriptions</p>
                <div class="flex items-end gap-2">
                    <span
                        class="text-3xl font-black text-[#121617] dark:text-white leading-none">{{ number_format($stats['active_subscriptions']) }}</span>
                </div>
            </div>
            <div
                class="p-6 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-800 rounded-xl shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Estimated MRR</p>
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-black text-[#121617] dark:text-white leading-none">Rs.
                        {{ number_format($stats['total_revenue'] / 1000, 1) }}k</span>
                    <span class="text-xs text-gray-400 font-medium mb-1">monthly</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>