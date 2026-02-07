<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl sm:text-3xl font-black text-[#121617] dark:text-white tracking-tight">Business Owners</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 font-medium">Manage tenant accounts and monitor their activity</p>
        </div>
    </x-slot>

    <!-- Dashboard Grid -->
    <div class="space-y-4 sm:space-y-6">
        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4 rounded-xl text-green-700 dark:text-green-400 text-sm font-bold flex items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            <div class="bg-white dark:bg-background-dark border border-gray-200 dark:border-white/10 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Total Tenants</p>
                        <p class="text-3xl font-black text-[#121617] dark:text-white">{{ $businessOwners->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-2xl">group</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-background-dark border border-gray-200 dark:border-white/10 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Active Plans</p>
                        <p class="text-3xl font-black text-[#121617] dark:text-white">
                            {{ $businessOwners->where('plan_expires_at', '>', now())->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600 text-2xl">check_circle</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-background-dark border border-gray-200 dark:border-white/10 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Expired Plans</p>
                        <p class="text-3xl font-black text-[#121617] dark:text-white">
                            {{ $businessOwners->where('plan_expires_at', '<', now())->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-red-500/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-600 text-2xl">error</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Utility Bar -->
        <div class="bg-white dark:bg-background-dark p-4 rounded-xl border border-gray-200 dark:border-white/10 flex flex-wrap gap-4 items-center">
            <form action="{{ route('superadmin.business-owners.index') }}" method="GET" class="flex-1 flex flex-col sm:flex-row flex-wrap gap-4 items-stretch sm:items-center">
                <div class="relative flex-1 min-w-0 sm:min-w-[300px]">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input name="search" value="{{ request('search') }}"
                        class="w-full pl-11 pr-4 py-2.5 bg-background-light dark:bg-white/5 border-none rounded-lg focus:ring-2 focus:ring-primary text-sm transition-all"
                        placeholder="Search by name or email..." type="text" />
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <a href="{{ route('superadmin.business-owners.index') }}"
                        class="p-2.5 text-gray-400 hover:text-primary transition-colors flex items-center justify-center">
                        <span class="material-symbols-outlined">refresh</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white dark:bg-background-dark border border-gray-200 dark:border-white/10 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-white/10 bg-gray-50/50 dark:bg-white/5">
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Business Name</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-bold text-gray-400 uppercase tracking-widest hidden md:table-cell">Email</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-bold text-gray-400 uppercase tracking-widest hidden lg:table-cell">Plan</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-bold text-gray-400 uppercase tracking-widest hidden lg:table-cell">Expires</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                        @forelse ($businessOwners as $owner)
                            <tr class="hover:bg-background-light dark:hover:bg-white/5 transition-colors group">
                                <td class="px-4 sm:px-6 py-4 sm:py-5">
                                    <div>
                                        <span class="font-bold text-[#121617] dark:text-white block">{{ $owner->name }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 md:hidden">{{ $owner->email }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 sm:py-5 hidden md:table-cell">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $owner->email }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 sm:py-5 hidden lg:table-cell">
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-primary/10 text-primary">
                                        {{ $owner->saasPlan->name ?? 'No Plan' }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 sm:py-5 hidden lg:table-cell">
                                    @if($owner->plan_expires_at)
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $owner->plan_expires_at->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400 italic">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4 sm:py-5 text-center">
                                    @if($owner->isPlanActive())
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-2"></span>
                                            Expired
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4 sm:py-5">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('superadmin.business-owners.show', $owner) }}"
                                            class="p-2 text-gray-400 hover:text-primary transition-colors rounded-lg hover:bg-background-light dark:hover:bg-white/5"
                                            title="View Details">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-4">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center">
                                            <span class="material-symbols-outlined text-gray-400 text-4xl">group_off</span>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400 font-bold">No business owners found</p>
                                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                                                {{ request('search') ? 'Try adjusting your search.' : 'No tenants have been registered yet.' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($businessOwners->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/10">
                    {{ $businessOwners->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
