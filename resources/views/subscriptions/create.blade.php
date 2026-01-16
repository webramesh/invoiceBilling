<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-3xl font-black text-[#121617] dark:text-white tracking-tight">Add Subscription</h2>
            <p class="text-[#667f85] dark:text-gray-400 mt-1 font-medium text-sm">Configure a recurring service and billing schedule for a client.</p>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8">
        <form method="POST" action="{{ route('subscriptions.store') }}" class="space-y-8">
            @csrf

            <!-- Form Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-[#dce3e4] dark:border-slate-800 overflow-hidden">
                <div class="p-8 space-y-8">
                    
                    <!-- Section: Identity -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-2">
                        <div class="space-y-2">
                            <label for="client_id" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Select Client</label>
                            <div class="relative">
                                <select id="client_id" name="client_id" required
                                    class="w-full h-12 rounded-xl border border-[#dce3e4] dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all appearance-none text-sm font-bold">
                                    <option value="">Choose a client...</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ (old('client_id') == $client->id || $selectedClient == $client->id) ? 'selected' : '' }}>
                                            {{ $client->name }} ({{ $client->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('client_id') <p class="text-accent-red text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="service_id" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Service Type</label>
                            <select id="service_id" name="service_id" required
                                class="w-full h-12 rounded-xl border border-[#dce3e4] dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all appearance-none text-sm font-bold">
                                <option value="">Choose a service...</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} (Rs. {{ number_format($service->base_price) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id') <p class="text-accent-red text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <hr class="border-[#f1f3f4] dark:border-slate-800"/>

                    <!-- Section: Financials -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="billing_cycle_id" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Billing Cycle</label>
                            <select id="billing_cycle_id" name="billing_cycle_id" required
                                class="w-full h-12 rounded-xl border border-[#dce3e4] dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all appearance-none text-sm font-bold">
                                <option value="">Select cycle...</option>
                                @foreach($billingCycles as $cycle)
                                    <option value="{{ $cycle->id }}" {{ old('billing_cycle_id') == $cycle->id ? 'selected' : '' }}>
                                        {{ $cycle->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('billing_cycle_id') <p class="text-accent-red text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="price" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Custom Amount (Optional)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rs.</span>
                                <input id="price" name="price" value="{{ old('price') }}" type="number" step="0.01"
                                    class="w-full h-12 pl-12 rounded-xl border border-[#dce3e4] dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-bold" 
                                    placeholder="Leave empty for base price"/>
                            </div>
                            @error('price') <p class="text-accent-red text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Section: Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="start_date" class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Start Date</label>
                            <div class="relative">
                                <input id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required
                                    class="w-full h-12 rounded-xl border border-[#dce3e4] dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-bold uppercase" type="date"/>
                            </div>
                            @error('start_date') <p class="text-accent-red text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <hr class="border-[#f1f3f4] dark:border-slate-800"/>

                    <!-- Section: Automation & Notifications -->
                    <div class="space-y-6">
                        <h3 class="text-sm font-bold text-[#121617] dark:text-white uppercase tracking-wider px-1">Automation & Notifications</h3>
                        
                        <div class="flex items-center justify-between p-4 rounded-xl bg-background-light dark:bg-gray-800/50 border border-[#dce3e4] dark:border-gray-800 transition-all hover:border-primary/30">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#25D366]/10 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-[#25D366]">chat</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-[#121617] dark:text-white">Automatic WhatsApp Reminders</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Send payment alerts 3 days before the due date.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input name="whatsapp_notifications" type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 rounded-xl bg-background-light dark:bg-gray-800/50 border border-[#dce3e4] dark:border-gray-800 transition-all hover:border-primary/30">
                            <div class="flex items-center gap-4">
                                <div class="bg-primary/10 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-primary">mail</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-[#121617] dark:text-white">Automatic Email Invoicing</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Generate and email PDF invoices automatically on the billing date.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input name="email_notifications" type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 rounded-xl bg-background-light dark:bg-gray-800/50 border border-[#dce3e4] dark:border-gray-800 transition-all hover:border-primary/30">
                            <div class="flex items-center gap-4">
                                <div class="bg-primary/10 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-primary">event_repeat</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-[#121617] dark:text-white">Recursive Renewal</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Automatically renew subscription and extend validity.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input name="auto_renewal" type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Footer Actions -->
                <div class="bg-gray-50 dark:bg-slate-800/50 px-8 py-6 flex justify-end gap-3 border-t border-[#dce3e4] dark:border-slate-800">
                    <a href="{{ route('subscriptions.index') }}" class="px-6 py-2.5 rounded-xl border border-[#dce3e4] dark:border-slate-700 text-[#121617] dark:text-white text-sm font-bold hover:bg-white dark:hover:bg-slate-800 transition-all">
                        Discard
                    </a>
                    <button type="submit" class="px-8 py-2.5 rounded-xl bg-primary text-white text-sm font-black hover:brightness-110 shadow-lg shadow-primary/20 transition-all active:scale-95">
                        Assign Service
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>