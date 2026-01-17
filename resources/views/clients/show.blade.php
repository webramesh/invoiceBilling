<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-5">
            <a class="p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors"
                href="{{ route('clients.index') }}">
                <span class="material-symbols-outlined text-gray-500">arrow_back</span>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-black text-[#121617] dark:text-white tracking-tight">{{ $client->name }}
                    </h2>
                    <span
                        class="status-badge-{{ $client->status === 'active' ? 'active' : 'inactive' }} px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-500/10 text-green-600 border border-green-500/20">
                        {{ $client->status }}
                    </span>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-0.5 font-medium italic">{{ $client->company }}</p>
            </div>
        </div>
    </x-slot>

    <div class="p-8 space-y-8 max-w-7xl mx-auto">
        <!-- Action Buttons Bar -->
        <div class="flex flex-wrap gap-3 pb-2">
            <a href="{{ route('clients.edit', $client) }}"
                class="px-5 py-2.5 border border-primary text-primary rounded-lg font-bold text-sm hover:bg-primary/5 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">edit</span> Edit Client
            </a>
            <button
                class="px-5 py-2.5 border border-primary text-primary rounded-lg font-bold text-sm hover:bg-primary/5 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">receipt</span> Send Manual Invoice
            </button>
            <a href="{{ route('subscriptions.create', ['client_id' => $client->id]) }}"
                class="px-5 py-2.5 bg-primary text-white rounded-lg font-bold text-sm hover:bg-primary/90 shadow-sm transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">add_circle</span> Add Service
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Client Profile Section -->
            <section
                class="lg:col-span-1 bg-white dark:bg-background-dark p-6 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-white/5">
                    <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">person</span>
                    <h3 class="text-lg font-bold text-[#121617] dark:text-white">Client Profile</h3>
                </div>
                <div class="space-y-5">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Full Name</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $client->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Email Address</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-400 text-base">mail</span>
                            {{ $client->email }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">WhatsApp Number</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500 text-base">chat</span>
                            {{ $client->whatsapp_number ?? $client->phone ?? 'Not Provided' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Address</p>
                        <p
                            class="text-sm font-semibold text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-wrap">
                            {{ $client->address ?? 'No address provided' }}</p>
                    </div>
                </div>
            </section>

            <!-- Active Services Section -->
            <section
                class="lg:col-span-2 bg-white dark:bg-background-dark p-6 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-white/5">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">dns</span>
                        <h3 class="text-lg font-bold text-[#121617] dark:text-white">Active Services</h3>
                    </div>
                    <span
                        class="text-xs font-bold text-primary bg-primary/10 px-3 py-1 rounded-full uppercase tracking-tighter">
                        {{ $client->subscriptions->where('status', 'active')->count() }} Services Running
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-white/5">
                                <th class="pb-3 pl-2">Service</th>
                                <th class="pb-3">Billing Cycle</th>
                                <th class="pb-3">Next Renewal</th>
                                <th class="pb-3 text-right pr-2">Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($client->subscriptions as $sub)
                                <tr class="group hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <td class="py-4 pl-2">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-500 rounded-lg">
                                                <span class="material-symbols-outlined text-lg">
                                                    @php
                                                        $cat = strtolower($sub->service->category->name ?? '');
                                                        if (str_contains($cat, 'host'))
                                                            echo 'cloud';
                                                        elseif (str_contains($cat, 'domain'))
                                                            echo 'language';
                                                        elseif (str_contains($cat, 'mail'))
                                                            echo 'alternate_email';
                                                        else
                                                            echo 'stat_0';
                                                    @endphp
                                                </span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                                    {{ $sub->service->name }}</p>
                                                <p class="text-[11px] text-gray-400">{{ $sub->status }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <span
                                            class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $sub->billingCycle->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="py-4">
                                        <span
                                            class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $sub->next_billing_date->format('M d, Y') }}</span>
                                    </td>
                                    <td class="py-4 text-right pr-2">
                                        <span class="text-sm font-bold text-gray-800 dark:text-gray-200">Rs.
                                            {{ number_format($sub->price, 2) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-400 italic text-sm">No active services
                                        assigned yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- Communication History Section -->
        <section
            class="bg-white dark:bg-background-dark p-6 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-white/5">
                <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">history_edu</span>
                <h3 class="text-lg font-bold text-[#121617] dark:text-white">Communication History</h3>
            </div>
            <div class="space-y-4">
                @forelse($client->notifications as $notification)
                    <div
                        class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/5">
                        <div
                            class="size-10 rounded-full {{ $notification->channel === 'email' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600' : 'bg-green-100 dark:bg-green-900/30 text-green-600' }} flex items-center justify-center shrink-0">
                            <span
                                class="material-symbols-outlined">{{ $notification->channel === 'email' ? 'mail' : 'chat' }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $notification->type }}</p>
                                <span
                                    class="text-[10px] font-bold text-gray-400 uppercase">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $notification->channel === 'email' ? 'Email sent to ' . $client->email : 'WhatsApp message sent' }}
                            </p>
                        </div>
                        <div
                            class="flex items-center gap-1.5 px-3 py-1 {{ $notification->status === 'sent' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} rounded-full">
                            <span
                                class="material-symbols-outlined text-sm">{{ $notification->status === 'sent' ? 'check_circle' : 'error' }}</span>
                            <span class="text-[10px] font-bold uppercase">{{ $notification->status }}</span>
                        </div>
                    </div>
                @empty
                    <div
                        class="p-8 text-center text-gray-400 italic text-sm border-2 border-dashed border-gray-100 dark:border-white/5 rounded-xl">
                        No communication history recorded for this client.
                    </div>
                @endforelse

                @if($client->notifications->count() > 0)
                    <div class="flex justify-center pt-4">
                        <button class="text-xs font-bold text-primary hover:underline flex items-center gap-2">
                            View All Logs <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </button>
                    </div>
                @endif
            </div>
        </section>
    </div>
</x-app-layout>