<x-app-layout>
    <div class="max-w-[1400px] mx-auto space-y-8">
        <!-- Page Heading & Metrics -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
            <div>
                <h2 class="text-4xl font-black tracking-tight text-[#121617] dark:text-white">Invoices</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1 font-medium">Streamline your billing and client communications.</p>
            </div>
            <!-- Stats Section -->
            <div class="flex gap-4">
                <div class="flex min-w-[220px] flex-col gap-1 rounded-xl p-5 border border-[#dce3e4] bg-white dark:bg-white/5 dark:border-white/10 shadow-sm">
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Total Outstanding</p>
                    <div class="flex items-baseline gap-2">
                        <p class="text-[#121617] dark:text-white text-2xl font-black">Rs. {{ number_format($stats['total_outstanding'], 2) }}</p>
                    </div>
                </div>
                <div class="flex min-w-[220px] flex-col gap-1 rounded-xl p-5 border border-[#dce3e4] bg-white dark:bg-white/5 dark:border-white/10 shadow-sm">
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Total Paid (Month)</p>
                    <div class="flex items-baseline gap-2">
                        <p class="text-[#121617] dark:text-white text-2xl font-black">Rs. {{ number_format($stats['total_paid_month'], 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Table Container -->
        <div class="bg-white dark:bg-white/5 border border-[#e4e7eb] dark:border-white/10 rounded-xl overflow-hidden shadow-sm">
            <!-- Filters Utility Bar -->
            <div class="p-4 border-b border-[#e4e7eb] dark:border-white/10 flex flex-col md:flex-row items-center justify-between bg-gray-50/50 dark:bg-transparent gap-4">
                <div class="flex gap-2 p-1 bg-gray-100 dark:bg-white/5 rounded-lg">
                    <a href="{{ route('invoices.index') }}" 
                        class="px-4 py-1.5 rounded-md text-sm font-bold {{ !request('status') ? 'bg-white dark:bg-white/10 text-primary shadow-sm' : 'text-gray-500' }}">
                        All Invoices
                    </a>
                    <a href="{{ route('invoices.index', ['status' => 'paid']) }}" 
                        class="px-4 py-1.5 rounded-md text-sm font-bold {{ request('status') === 'paid' ? 'bg-white dark:bg-white/10 text-primary shadow-sm' : 'text-gray-500' }}">
                        Paid
                    </a>
                    <a href="{{ route('invoices.index', ['status' => 'unpaid']) }}" 
                        class="px-4 py-1.5 rounded-md text-sm font-bold {{ request('status') === 'unpaid' ? 'bg-white dark:bg-white/10 text-primary shadow-sm' : 'text-gray-500' }}">
                        Unpaid
                    </a>
                </div>
                <form id="search-form" action="{{ route('invoices.index') }}" method="GET" class="hidden"></form>
                <div class="flex items-center gap-3">
                    @if(request('search'))
                        <a href="{{ route('invoices.index') }}" class="text-xs font-bold text-gray-400 hover:text-red-500 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">close</span> Clear Search
                        </a>
                    @endif
                    <button class="flex items-center gap-2 text-primary font-bold text-sm px-4 py-2 rounded-lg hover:bg-primary/10">
                        <span class="material-symbols-outlined text-sm">filter_list</span>
                        Advanced Filters
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-white/5 text-[11px] font-black uppercase tracking-widest text-gray-400">
                            <th class="px-6 py-4">Invoice #</th>
                            <th class="px-6 py-4">Client Name</th>
                            <th class="px-6 py-4">Service</th>
                            <th class="px-6 py-4">Amount</th>
                            <th class="px-6 py-4">Due Date</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e4e7eb] dark:divide-white/10">
                        @forelse($invoices as $invoice)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-4 font-bold text-sm text-primary">#{{ $invoice->invoice_number }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold uppercase">
                                            {{ substr($invoice->client->name, 0, 2) }}
                                        </div>
                                        <span class="text-sm font-semibold">{{ $invoice->client->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $invoice->subscription->service->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 font-bold text-sm">Rs. {{ number_format($invoice->total, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $invoice->due_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = [
                                            'paid' => 'bg-green-500/10 text-green-600',
                                            'unpaid' => 'bg-orange-500/10 text-orange-600',
                                            'overdue' => 'bg-red-500/10 text-red-600',
                                        ][$invoice->status] ?? 'bg-gray-100 text-gray-600';
                                        
                                        // Overdue calculation logic
                                        $displayStatus = $invoice->status;
                                        if($invoice->status === 'unpaid' && $invoice->due_date->isPast()) {
                                            $statusClass = 'bg-red-500/10 text-red-600';
                                            $displayStatus = 'overdue';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-tighter {{ $statusClass }}">
                                        {{ $displayStatus }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('invoices.send-whatsapp', $invoice) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-primary hover:bg-primary/90 text-white flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-transform active:scale-95">
                                                <span class="material-symbols-outlined text-sm">chat_bubble</span>
                                                WhatsApp
                                            </button>
                                        </form>
                                        <form action="{{ route('invoices.send-email', $invoice) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-transform active:scale-95">
                                                <span class="material-symbols-outlined text-sm">mail</span>
                                                Email
                                            </button>
                                        </form>
                                        <a href="{{ route('invoices.show', $invoice) }}" class="p-1.5 text-gray-400 hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-xl">visibility</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic text-sm">
                                    No invoices found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50/30 dark:bg-white/5 border-t border-[#e4e7eb] dark:border-white/10 flex items-center justify-between text-xs font-bold text-gray-400 uppercase tracking-widest">
                <p>Showing {{ $invoices->firstItem() ?? 0 }}-{{ $invoices->lastItem() ?? 0 }} of {{ $invoices->total() }} results</p>
                <div class="flex gap-2">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>