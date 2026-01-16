<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Client Details') }}: {{ $client->name }}
            </h2>
            <div class="flex">
                <a href="{{ route('clients.edit', $client) }}"
                    class="inline-flex items-center px-4 py-2 bg-yellow-600 dark:bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-2">
                    {{ __('Edit Client') }}
                </a>
                <a href="{{ route('clients.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Client Info Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">
                                {{ __('General Information') }}
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ __('Email') }}
                                    </p>
                                    <p class="text-base text-gray-900 dark:text-gray-100">{{ $client->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ __('Phone') }}
                                    </p>
                                    <p class="text-base text-gray-900 dark:text-gray-100">{{ $client->phone ?? '-' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ __('Company') }}
                                    </p>
                                    <p class="text-base text-gray-900 dark:text-gray-100">{{ $client->company ?? '-' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ __('WhatsApp') }}
                                    </p>
                                    <p class="text-base text-gray-900 dark:text-gray-100">
                                        {{ $client->whatsapp_number ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ __('Status') }}
                                    </p>
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $client->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ __('Address') }}
                                    </p>
                                    <p class="text-base text-gray-900 dark:text-gray-100 whitespace-pre-wrap">
                                        {{ $client->address ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subscriptions and Invoices -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Subscriptions Section -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4 border-b pb-2">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Active Services') }}
                                </h3>
                                <a href="#"
                                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Assign New Service') }}</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-widest">
                                                {{ __('Service') }}</th>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-widest">
                                                {{ __('Next Billing') }}</th>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-widest">
                                                {{ __('Price') }}</th>
                                            <th
                                                class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-widest">
                                                {{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse ($client->subscriptions as $subscription)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $subscription->service->name }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $subscription->next_billing_date->format('M d, Y') }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ number_format($subscription->price, 2) }}</td>
                                                <td class="px-4 py-2 text-right">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                        {{ ucfirst($subscription->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4"
                                                    class="px-4 py-2 text-sm text-center text-gray-500 dark:text-gray-400">
                                                    {{ __('No active services found.') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Invoices Section -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4 border-b pb-2">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Recent Invoices') }}
                                </h3>
                                <a href="#"
                                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Create New Invoice') }}</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-widest">
                                                {{ __('Invoice #') }}</th>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-widest">
                                                {{ __('Date') }}</th>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-widest">
                                                {{ __('Amount') }}</th>
                                            <th
                                                class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-widest">
                                                {{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse ($client->invoices as $invoice)
                                            <tr>
                                                <td
                                                    class="px-4 py-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    <a href="#">{{ $invoice->invoice_number }}</a>
                                                </td>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $invoice->issue_date->format('M d, Y') }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ number_format($invoice->total, 2) }}</td>
                                                <td class="px-4 py-2 text-right">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                        {{ ucfirst($invoice->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4"
                                                    class="px-4 py-2 text-sm text-center text-gray-500 dark:text-gray-400">
                                                    {{ __('No recent invoices found.') }}</td>
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
    </div>
</x-app-layout>