<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Invoice') }}: {{ $invoice->invoice_number }}
            </h2>
            <div class="flex">
                @if($invoice->status !== 'paid')
                    <form action="{{ route('invoices.mark-as-paid', $invoice) }}" method="POST" class="mr-2">
                        @csrf
                        <x-primary-button class="bg-green-600 hover:bg-green-700 active:bg-green-900 focus:ring-green-500">
                            {{ __('Mark as Paid') }}
                        </x-primary-button>
                    </form>
                @endif
                <a href="{{ route('invoices.download', $invoice) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Download PDF') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Invoice Header -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-200 dark:border-gray-700 pb-8 mb-8">
                        <div>
                            <h3 class="text-xl font-bold mb-4">{{ __('Billed To') }}</h3>
                            <p class="font-bold text-lg">{{ $invoice->client->name }}</p>
                            @if($invoice->client->company)
                                <p class="text-gray-600 dark:text-gray-400">{{ $invoice->client->company }}</p>
                            @endif
                            <p class="text-gray-600 dark:text-gray-400">{{ $invoice->client->email }}</p>
                            <p class="text-gray-600 dark:text-gray-400">{{ $invoice->client->phone }}</p>
                            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                                {!! nl2br(e($invoice->client->address)) !!}
                            </div>
                        </div>
                        <div class="md:text-right">
                            <h3 class="text-xl font-bold mb-4">{{ __('Invoice Details') }}</h3>
                            <p><span class="font-bold">{{ __('Invoice Number') }}:</span> {{ $invoice->invoice_number }}
                            </p>
                            <p><span class="font-bold">{{ __('Issue Date') }}:</span>
                                {{ $invoice->issue_date->format('M d, Y') }}</p>
                            <p><span class="font-bold">{{ __('Due Date') }}:</span>
                                {{ $invoice->due_date->format('M d, Y') }}</p>
                            <div class="mt-4">
                                <span
                                    class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full 
                                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ strtoupper($invoice->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="mb-8">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-widest">
                                        {{ __('Description') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-widest">
                                        {{ __('Qty') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-widest">
                                        {{ __('Unit Price') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-widest">
                                        {{ __('Total') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($invoice->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 text-sm">{{ $item->description }}</td>
                                        <td class="px-6 py-4 text-sm text-center">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 text-sm text-right">{{ number_format($item->unit_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-right font-medium">
                                            {{ number_format($item->total, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-4 text-right font-bold text-gray-500 dark:text-gray-400">
                                        {{ __('Subtotal') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-lg">
                                        {{ number_format($invoice->subtotal, 2) }}
                                    </td>
                                </tr>
                                @if($invoice->tax > 0)
                                    <tr>
                                        <td colspan="3"
                                            class="px-6 py-4 text-right font-bold text-gray-500 dark:text-gray-400">
                                            {{ __('Tax') }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-lg">
                                            {{ number_format($invoice->tax, 2) }}
                                        </td>
                                    </tr>
                                @endif
                                <tr class="bg-gray-50 dark:bg-gray-700">
                                    <td colspan="3"
                                        class="px-6 py-4 text-right font-black text-gray-900 dark:text-white uppercase tracking-widest">
                                        {{ __('Total Amount') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-right font-black text-2xl text-indigo-600 dark:text-indigo-400">
                                        {{ number_format($invoice->total, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Payments History -->
                    @if($invoice->payments->count() > 0)
                        <div class="mt-12 bg-gray-50 dark:bg-gray-900/50 p-6 rounded-lg">
                            <h3 class="text-lg font-bold mb-4">{{ __('Payment History') }}</h3>
                            <table class="min-w-full">
                                <thead>
                                    <tr
                                        class="text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                        <th class="pb-2">{{ __('Date') }}</th>
                                        <th class="pb-2">{{ __('Method') }}</th>
                                        <th class="pb-2">{{ __('Reference') }}</th>
                                        <th class="pb-2 text-right">{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach($invoice->payments as $payment)
                                        <tr class="border-t border-gray-200 dark:border-gray-700">
                                            <td class="py-3">{{ $payment->payment_date->format('M d, Y H:i') }}</td>
                                            <td class="py-3">{{ ucfirst($payment->payment_method) }}</td>
                                            <td class="py-3 text-gray-500">{{ $payment->transaction_reference ?? '-' }}</td>
                                            <td class="py-3 text-right font-bold">{{ number_format($payment->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($invoice->notes)
                        <div class="mt-8">
                            <h4 class="font-bold mb-2">{{ __('Notes') }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>