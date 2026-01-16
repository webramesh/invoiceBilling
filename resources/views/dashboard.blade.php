<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Revenue Month -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            {{ __('Revenue (This Month)') }}</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">Rs.
                            {{ number_format($stats['revenue_month'], 2) }}</p>
                    </div>
                </div>

                <!-- Overdue Invoices -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            {{ __('Overdue Invoices') }}</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">
                            {{ $stats['overdue_invoices'] }}</p>
                    </div>
                </div>

                <!-- Active Subscriptions -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            {{ __('Active Services') }}</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">
                            {{ $stats['active_subscriptions'] }}</p>
                    </div>
                </div>

                <!-- Total Clients -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            {{ __('Total Clients') }}</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $stats['total_clients'] }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Upcoming Renewals -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-widest">
                            {{ __('Upcoming Renewals (Next 30 Days)') }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr
                                        class="text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                        <th class="pb-3">{{ __('Client') }}</th>
                                        <th class="pb-3">{{ __('Service') }}</th>
                                        <th class="pb-3">{{ __('Date') }}</th>
                                        <th class="pb-3 text-right">{{ __('Price') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @forelse($upcomingRenewals as $renewal)
                                        <tr class="border-t border-gray-100 dark:border-gray-700">
                                            <td class="py-4">
                                                <a href="{{ route('clients.show', $renewal->client) }}"
                                                    class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    {{ $renewal->client->name }}
                                                </a>
                                            </td>
                                            <td class="py-4 text-gray-600 dark:text-gray-300">{{ $renewal->service->name }}
                                            </td>
                                            <td class="py-4">{{ $renewal->next_billing_date->format('M d, Y') }}</td>
                                            <td class="py-4 text-right font-bold">{{ number_format($renewal->price, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-4 text-center text-gray-500">
                                                {{ __('No upcoming renewals.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Invoices -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-widest">
                            {{ __('Recent Invoices') }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr
                                        class="text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                        <th class="pb-3">{{ __('Invoice #') }}</th>
                                        <th class="pb-3">{{ __('Client') }}</th>
                                        <th class="pb-3">{{ __('Status') }}</th>
                                        <th class="pb-3 text-right">{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @forelse($recentInvoices as $invoice)
                                        <tr class="border-t border-gray-100 dark:border-gray-700">
                                            <td class="py-4">
                                                <a href="{{ route('invoices.show', $invoice) }}"
                                                    class="font-medium text-gray-900 dark:text-white hover:text-indigo-600">
                                                    {{ $invoice->invoice_number }}
                                                </a>
                                            </td>
                                            <td class="py-4 text-gray-600 dark:text-gray-300">{{ $invoice->client->name }}
                                            </td>
                                            <td class="py-4">
                                                <span
                                                    class="px-2 py-1 text-xs font-bold rounded-full 
                                                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ strtoupper($invoice->status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 text-right font-bold">{{ number_format($invoice->total, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-4 text-center text-gray-500">
                                                {{ __('No recent invoices.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>