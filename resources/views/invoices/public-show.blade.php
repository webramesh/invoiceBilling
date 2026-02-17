<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ request()->cookie('theme', 'light') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $invoice->invoice_number }} - {{ config('app.name') }}</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/geist/geist.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/all.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Geist', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-[#121617] text-[#121617] dark:text-gray-200 antialiased min-h-screen">
    <div class="max-w-4xl mx-auto py-12 px-6">
        <!-- Logo/Header -->
        <div class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-primary">{{ config('app.name', 'InvoiceApp') }}
                </h1>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1">Official Invoice</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('invoices.public.download', $invoice->hash) }}"
                    class="flex items-center gap-2 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-50 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-lg">download</span>
                    PDF
                </a>
            </div>
        </div>

        <div
            class="bg-white dark:bg-[#1c2123] rounded-3xl shadow-xl shadow-primary/5 border border-white dark:border-white/5 overflow-hidden">
            <div class="p-8 md:p-12">
                <!-- Status Banner -->
                <div
                    class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6 pb-12 border-b border-gray-100 dark:border-white/5">
                    <div>
                        <h2 class="text-4xl font-black mb-2">#{{ $invoice->invoice_number }}</h2>
                        <p class="text-gray-500 font-medium">Issued on {{ $invoice->issue_date->format('M d, Y') }}</p>
                    </div>
                    @php
                        $isPaid = $invoice->status === 'paid';
                        $isOverdue = $invoice->status === 'unpaid' && $invoice->due_date->isPast();
                    @endphp
                    <div class="flex flex-col items-end">
                        <span
                            class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest 
                            {{ $isPaid ? 'bg-green-500/10 text-green-500' : ($isOverdue ? 'bg-red-500/10 text-red-500' : 'bg-orange-500/10 text-orange-500') }}">
                            {{ $isPaid ? 'Paid' : ($isOverdue ? 'Overdue' : 'Unpaid') }}
                        </span>
                        <p class="mt-2 text-sm font-bold {{ $isPaid ? 'text-green-500' : 'text-gray-400' }}">
                            {{ $isPaid ? 'Thank you for your payment!' : 'Due by ' . $invoice->due_date->format('M d, Y') }}
                        </p>
                    </div>
                </div>

                <!-- Addresses -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-4">Billed To</p>
                        <h3 class="text-xl font-bold mb-2">{{ $invoice->client->name }}</h3>
                        @if($invoice->client->company)
                            <p class="text-gray-500 font-medium mb-1">{{ $invoice->client->company }}</p>
                        @endif
                        <p class="text-gray-500 font-medium mb-1">{{ $invoice->client->email }}</p>
                        <p class="text-gray-500 font-medium">{{ $invoice->client->phone }}</p>
                        <div class="mt-4 text-sm text-gray-500 leading-relaxed">
                            {!! nl2br(e($invoice->client->address)) !!}
                        </div>
                    </div>
                    <div class="md:text-right">
                        <p class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-4">From</p>
                        <h3 class="text-xl font-bold mb-2">{{ config('app.name', 'InvoiceApp') }}</h3>
                        <p class="text-gray-500 font-medium">billing@example.com</p>
                        <p class="text-gray-500 font-medium">+1 (234) 567-890</p>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto -mx-4 md:mx-0 mb-12">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-[11px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 dark:border-white/5">
                                <th class="px-4 py-4">Description</th>
                                <th class="px-4 py-4 text-center">Qty</th>
                                <th class="px-4 py-4 text-right">Price</th>
                                <th class="px-4 py-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="px-4 py-6">
                                        <p class="font-bold text-sm">{{ $item->description }}</p>
                                    </td>
                                    <td class="px-4 py-6 text-center text-sm font-medium">{{ $item->quantity }}</td>
                                    <td class="px-4 py-6 text-right text-sm font-medium">Rs.
                                        {{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-4 py-6 text-right text-sm font-bold">Rs.
                                        {{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"></td>
                                <td
                                    class="px-4 py-6 text-right text-sm font-bold text-gray-400 uppercase tracking-widest">
                                    Subtotal</td>
                                <td class="px-4 py-6 text-right font-bold">Rs.
                                    {{ number_format($invoice->subtotal, 2) }}</td>
                            </tr>
                            @if($invoice->tax > 0)
                                <tr>
                                    <td colspan="2"></td>
                                    <td
                                        class="px-4 py-2 text-right text-sm font-bold text-gray-400 uppercase tracking-widest">
                                        Tax</td>
                                    <td class="px-4 py-2 text-right font-bold">Rs. {{ number_format($invoice->tax, 2) }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="2"></td>
                                <td class="px-4 py-8 text-right">
                                    <p class="text-sm font-black text-primary uppercase tracking-widest">Grand Total</p>
                                </td>
                                <td class="px-4 py-8 text-right">
                                    <p class="text-3xl font-black text-primary">Rs.
                                        {{ number_format($invoice->total, 2) }}</p>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Payments -->
                @if($invoice->payments->count() > 0)
                    <div class="bg-gray-50 dark:bg-white/[0.02] rounded-2xl p-6 md:p-8 mb-12">
                        <h4 class="text-sm font-black uppercase tracking-widest text-primary mb-6">Payment History</h4>
                        <div class="space-y-4">
                            @foreach($invoice->payments as $payment)
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-full bg-white dark:bg-white/5 flex items-center justify-center shadow-sm">
                                            <span class="material-symbols-outlined text-green-500">check</span>
                                        </div>
                                        <div>
                                            <p class="font-bold uppercase tracking-tighter">
                                                {{ ucfirst($payment->payment_method) }}</p>
                                            <p class="text-gray-400 text-[11px] font-bold">
                                                {{ $payment->payment_date->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <p class="font-black">Rs. {{ number_format($payment->amount, 2) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($invoice->notes)
                    <div class="border-t border-gray-100 dark:border-white/5 pt-8">
                        <p class="text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2">Important Notes</p>
                        <p class="text-sm text-gray-500 leading-relaxed italic">{{ $invoice->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Pay Button (Placeholder) -->
            @if(!$isPaid)
                <div class="bg-primary p-8 text-center">
                    <button
                        class="bg-white text-primary px-12 py-4 rounded-2xl font-black uppercase tracking-widest hover:scale-105 transition-transform shadow-xl">
                        Make Online Payment
                    </button>
                    <p class="text-white/60 text-[10px] font-bold uppercase tracking-tighter mt-4">Safe & Secure
                        Transactions</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Powered by {{ config('app.name') }}</p>
        </div>
    </div>
</body>

</html>