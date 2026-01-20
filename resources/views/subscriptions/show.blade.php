<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('subscriptions.index') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    {{ __('Back to Subscriptions') }}
                </a>
                <span class="text-gray-300">/</span>
                <span class="text-gray-900 dark:text-white font-medium">{{ $subscription->id ? 'SUB-'.$subscription->id : $subscription->service->name }}</span>
            </div>

            <div class="flex gap-3">
                <a href="#" onclick="window.print(); return false;" class="px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white font-medium hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors flex items-center gap-2">
                    <svg class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 14h12v8H6z"/></svg>
                    {{ __('Print') }}
                </a>
                <a href="{{ route('subscriptions.edit', $subscription) }}" class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:brightness-110 shadow transition-all flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6M5 5h.01M6 12h12M6 19h12"/></svg>
                    {{ __('Edit Subscription') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden mb-8">
                <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex flex-col md:flex-row justify-between items-start gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                            </span>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">{{ $subscription->service->name }}</h1>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">Subscription ID: <span class="font-mono text-sm uppercase">{{ $subscription->id ? 'SUB-'.$subscription->id : '' }}</span></p>
                    </div>

                    <div class="{{ $subscription->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }} px-4 py-2 rounded-full flex items-center gap-2 border {{ $subscription->status === 'active' ? 'border-green-200 dark:border-green-800/50' : 'border-amber-200 dark:border-amber-800/50' }}">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <span class="font-bold text-sm uppercase tracking-wider">{{ ucfirst($subscription->status) }}</span>
                    </div>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-10">
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800 pb-2">Service Summary</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Client Name</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $subscription->client->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Billing Cycle</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $subscription->billingCycle->name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800 pb-2">Financial Overview</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Base Amount</p>
                                <p class="font-semibold text-gray-900 dark:text-white">${{ number_format($subscription->price, 2) }}</p>
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Tax Rate</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $subscription->tax_rate ?? '0' }}%</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-blue-600 font-bold uppercase mb-1">Total/Cycle</p>
                                    <p class="text-xl font-bold text-blue-600">${{ number_format(optional($subscription)->price * (1 + (optional($subscription)->tax_rate ?? 0)/100), 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800 pb-2">Renewal Timeline</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Start Date</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $subscription->start_date ? $subscription->start_date->format('M d, Y') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Next Billing Date</p>
                                <div class="flex items-center gap-2">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $subscription->next_billing_date ? $subscription->next_billing_date->format('M d, Y') : '-' }}</p>
                                    @if($subscription->next_billing_date)
                                        @php
                                            $days = now()->diffInDays($subscription->next_billing_date, false);
                                        @endphp
                                        <span class="text-[10px] bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 px-2 py-0.5 rounded font-bold uppercase">{{ $days }} Days Left</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800 pb-2">Automation Settings</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">WhatsApp Alerts</p>
                                    <p class="text-sm font-semibold text-{{ $subscription->whatsapp_notifications ? 'green' : 'gray' }}-600">{{ $subscription->whatsapp_notifications ? 'Enabled' : 'Disabled' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m0 0l4-4m-4 4l4 4"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Email Invoicing</p>
                                    <p class="text-sm font-semibold text-{{ $subscription->email_notifications ? 'green' : 'gray' }}-600">{{ $subscription->email_notifications ? 'Enabled' : 'Disabled' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 border-t border-gray-100 dark:border-gray-800">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Invoice History</h3>
                            <a href="{{ route('invoices.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">View All Invoices</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50 dark:bg-gray-800/50">
                                        <th class="px-4 py-3 first:rounded-l-lg">Invoice ID</th>
                                        <th class="px-4 py-3">Date Issued</th>
                                        <th class="px-4 py-3">Period Covered</th>
                                        <th class="px-4 py-3 text-right">Amount</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3 last:rounded-r-lg text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @foreach($subscription->invoices as $invoice)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                            <td class="px-4 py-4 font-mono text-sm">{{ $invoice->invoice_number }}</td>
                                            <td class="px-4 py-4 text-sm">{{ $invoice->issue_date ? $invoice->issue_date->format('M d, Y') : '' }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-500">{{ $invoice->period_text ?? '-' }}</td>
                                            <td class="px-4 py-4 text-sm font-bold text-right">${{ number_format($invoice->total, 2) }}</td>
                                            <td class="px-4 py-4">
                                                <span class="text-[10px] font-bold uppercase bg-green-50 text-green-700 dark:bg-green-900/20 px-2 py-1 rounded">{{ ucfirst($invoice->status) }}</span>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-800">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 px-8 py-4 flex justify-between items-center text-xs text-gray-500 border-t border-gray-100 dark:border-gray-800">
                    <p>Last updated by {{ $subscription->updated_by ?? 'Admin' }} on {{ $subscription->updated_at ? $subscription->updated_at->format('M d, Y \\a\\t H:i') : '' }}</p>
                    <div class="flex items-center gap-4">
                        <a class="hover:text-blue-600 transition-colors" href="#">Terms of Service</a>
                        <a class="hover:text-blue-600 transition-colors" href="#">Privacy Policy</a>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-200 rounded-xl p-6 flex gap-4 items-start">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                <div>
                    <h4 class="font-bold text-blue-600 mb-1">Automatic Renewal Notice</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">This subscription is set to renew automatically. A draft invoice will be generated before the next billing date. To prevent renewal, cancel or modify the subscription at least 7 days before the next billing date.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>