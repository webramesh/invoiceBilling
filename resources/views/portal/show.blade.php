<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Portal | {{ $client->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Main Content -->
        <main class="py-10">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Card -->
                <div class="bg-[#121617] rounded-3xl p-8 mb-8 text-white shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            <div>
                                <h1 class="text-3xl font-black tracking-tight mb-2">Welcome, {{ $client->name }}</h1>
                                <p class="text-slate-400 font-medium">Your Service Dashboard & Invoice History</p>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Total Outstanding</span>
                                <span class="text-4xl font-black text-teal-400">Rs. {{ number_format($invoices->where('status', 'unpaid')->sum('total'), 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Decoration -->
                    <div class="absolute top-0 right-0 -mr-20 -mt-20 size-64 bg-teal-500/10 rounded-full blur-3xl"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Invoice History -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="font-bold text-slate-800">Invoice History</h3>
                                <span class="text-[10px] font-black bg-slate-100 text-slate-500 px-2 py-0.5 rounded uppercase leading-none">All Time</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-slate-50/50">
                                        <tr>
                                            <th class="px-6 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Invoice</th>
                                            <th class="px-6 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Amount</th>
                                            <th class="px-6 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                                            <th class="px-6 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        @foreach($invoices as $invoice)
                                            <tr class="hover:bg-slate-50 transition-colors">
                                                <td class="px-6 py-4">
                                                    <p class="text-sm font-bold text-slate-800 leading-none mb-1">{{ $invoice->invoice_number }}</p>
                                                    <p class="text-[10px] font-medium text-slate-400">{{ $invoice->issue_date->format('M d, Y') }}</p>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-sm font-black text-slate-800">Rs. {{ number_format($invoice->total, 2) }}</span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $invoice->status == 'paid' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                                                        {{ $invoice->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <a href="{{ route('invoices.public.show', $invoice->hash) }}" class="text-teal-600 hover:text-teal-700 text-xs font-bold transition-colors">View Details</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Active Services -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6">
                            <h3 class="font-bold text-slate-800 mb-4">Active Services</h3>
                            <div class="space-y-4">
                                @foreach($subscriptions as $sub)
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                        <div class="size-10 bg-white rounded-lg border border-slate-200 flex items-center justify-center text-teal-600">
                                            <span class="material-symbols-outlined">language</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-slate-800 truncate">{{ $sub->service->name }}</p>
                                            <p class="text-[10px] font-medium text-slate-400">Next Due: {{ $sub->next_billing_date->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-teal-600 rounded-2xl p-6 text-white shadow-lg shadow-teal-500/20">
                            <h4 class="font-bold mb-2">Need Help?</h4>
                            <p class="text-xs text-teal-100 leading-relaxed mb-4">If you have any questions regarding your invoices or services, please contact our support team.</p>
                            <a href="mailto:{{ $client->user->email ?? '' }}" class="inline-flex items-center gap-2 bg-white text-teal-600 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all hover:bg-teal-50">
                                <span class="material-symbols-outlined text-sm">mail</span> Contact Support
                            </a>
                        </div>
                    </div>
                </div>

                <footer class="mt-12 text-center text-slate-400">
                    <p class="text-[10px] font-bold uppercase tracking-widest">Powered by {{ config('app.name') }} SaaS Portal</p>
                </footer>
            </div>
        </main>
    </div>
</body>
</html>
