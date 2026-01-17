<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h2 class="text-3xl font-black text-[#121617] dark:text-white tracking-tight">Edit Subscription</h2>
                <p class="text-[#667f85] dark:text-gray-400 mt-1 font-medium text-sm">Update service configuration and
                    billing schedules for <span class="text-primary font-bold">{{ $subscription->client->name }}</span>.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 space-y-8">
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-4 flex items-start gap-3">
                <span class="material-symbols-outlined text-red-500">error</span>
                <div class="flex-1">
                    <p class="text-sm font-bold text-red-700 dark:text-red-400">Please correct the following errors:</p>
                    <ul class="list-disc list-inside text-xs text-red-600 dark:text-red-400 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-4 flex items-center gap-3">
                <span class="material-symbols-outlined text-red-500">error</span>
                <p class="text-sm font-bold text-red-700 dark:text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Dashboard Style Summary Area -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-primary/5 dark:bg-primary/10 p-5 rounded-2xl border border-primary/10 shadow-sm transition-transform hover:scale-[1.02]">
                <p class="text-[10px] font-black text-primary uppercase tracking-widest">Est. Contract Value</p>
                <p class="text-2xl font-black text-primary mt-1">Rs. {{ number_format($subscription->price) }}</p>
                <p class="text-[10px] font-bold text-primary/60 mt-1 uppercase">{{ $subscription->billingCycle->name }}
                    Cycle</p>
            </div>

            <div
                class="bg-green-500/5 p-5 rounded-2xl border border-green-500/10 shadow-sm transition-transform hover:scale-[1.02]">
                <p class="text-[10px] font-black text-green-600 uppercase tracking-widest">Service Status</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                    <p class="text-2xl font-black text-green-600 uppercase">{{ $subscription->status }}</p>
                </div>
                <p class="text-[10px] font-bold text-green-600/60 mt-1 uppercase">Since
                    {{ $subscription->created_at->format('M d, Y') }}
                </p>
            </div>

            <div
                class="bg-amber-500/5 p-5 rounded-2xl border border-amber-500/10 shadow-sm transition-transform hover:scale-[1.02]">
                <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Next Billing On</p>
                <p class="text-2xl font-black text-amber-600 mt-1">
                    {{ $subscription->next_billing_date->format('M d, Y') }}
                </p>
                <p class="text-[10px] font-bold text-amber-600/60 mt-1 uppercase">
                    {{ $subscription->next_billing_date->diffForHumans() }}
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('subscriptions.update', $subscription) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Form Card -->
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-[#dce3e4] dark:border-slate-800 overflow-hidden">
                <div class="p-8 space-y-8">

                    <!-- Section: Identity -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-2">
                        <div class="space-y-2">
                            <label for="client_id"
                                class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Locked
                                Client</label>
                            <div class="relative">
                                <select id="client_id" name="client_id" required
                                    class="w-full h-12 rounded-xl border border-[#dce3e4] dark:border-slate-800 bg-gray-50/30 dark:bg-slate-900/50 px-4 transition-all appearance-none text-sm font-bold opacity-75 cursor-not-allowed">
                                    <option value="{{ $subscription->client_id }}" selected>
                                        {{ $subscription->client->name }}
                                    </option>
                                    @foreach($clients as $client)
                                        @if($client->id != $subscription->client_id)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="client_id" value="{{ $subscription->client_id }}">
                        </div>

                        <div class="space-y-2">
                            <label for="service_id"
                                class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Service
                                Type</label>
                            <select id="service_id" name="service_id" required
                                class="w-full h-12 rounded-xl border border-[#dce3e4] dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all appearance-none text-sm font-bold">
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id', $subscription->service_id) == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr class="border-[#f1f3f4] dark:border-slate-800" />

                    <!-- Section: Financials -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="billing_cycle_id"
                                class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Billing
                                Cycle</label>
                            <select id="billing_cycle_id" name="billing_cycle_id" required
                                class="w-full h-12 rounded-xl border border-[#dce3e4] dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all appearance-none text-sm font-bold">
                                @foreach($billingCycles as $cycle)
                                    <option value="{{ $cycle->id }}" {{ old('billing_cycle_id', $subscription->billing_cycle_id) == $cycle->id ? 'selected' : '' }}>
                                        {{ $cycle->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="price"
                                class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Subscription
                                Amount</label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rs.</span>
                                <input id="price" name="price" value="{{ old('price', $subscription->price) }}"
                                    type="number" step="0.01" required
                                    class="w-full h-12 pl-12 rounded-xl border border-[#dce3e4] dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-bold" />
                            </div>
                        </div>
                    </div>

                    <!-- Section: Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label for="status"
                                class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Global
                                Status</label>
                            <select id="status" name="status" required
                                class="w-full h-12 rounded-xl border border-[#dce3e4] dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all appearance-none text-sm font-bold uppercase tracking-widest">
                                <option value="active" {{ old('status', $subscription->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="suspended" {{ old('status', $subscription->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="cancelled" {{ old('status', $subscription->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="start_date"
                                class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Service
                                Start</label>
                            <input id="start_date" name="start_date"
                                value="{{ old('start_date', $subscription->start_date->format('Y-m-d')) }}" required
                                class="w-full h-12 rounded-xl border border-[#dce3e4] dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-bold"
                                type="date" />
                        </div>

                        <div class="space-y-2">
                            <label for="next_billing_date"
                                class="text-xs font-black text-gray-400 uppercase tracking-widest px-1">Next Bill
                                Date</label>
                            <input id="next_billing_date" name="next_billing_date"
                                value="{{ old('next_billing_date', $subscription->next_billing_date->format('Y-m-d')) }}"
                                required
                                class="w-full h-12 rounded-xl border border-[#dce3e4] dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-bold"
                                type="date" />
                        </div>
                    </div>

                    <hr class="border-[#f1f3f4] dark:border-slate-800" />

                    <!-- Section: Automation & Notifications -->
                    <div class="space-y-6">
                        <h3 class="text-sm font-bold text-[#121617] dark:text-white uppercase tracking-wider px-1">
                            Automation & Notifications</h3>

                        <div
                            class="flex items-center justify-between p-4 rounded-xl bg-background-light dark:bg-gray-800/50 border border-[#dce3e4] dark:border-gray-800 transition-all hover:border-primary/30">
                            <div class="flex items-center gap-4">
                                <div class="bg-[#25D366]/10 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-[#25D366]">chat</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-[#121617] dark:text-white">Automatic WhatsApp
                                        Reminders</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Send payment alerts 3 days
                                        before the due date.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input name="whatsapp_notifications" type="checkbox" class="sr-only peer" {{ old('whatsapp_notifications', $subscription->whatsapp_notifications) ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                </div>
                            </label>
                        </div>

                        <div
                            class="flex items-center justify-between p-4 rounded-xl bg-background-light dark:bg-gray-800/50 border border-[#dce3e4] dark:border-gray-800 transition-all hover:border-primary/30">
                            <div class="flex items-center gap-4">
                                <div class="bg-primary/10 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-primary">mail</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-[#121617] dark:text-white">Automatic Email
                                        Invoicing</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Generate and email PDF invoices
                                        automatically on the billing date.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input name="email_notifications" type="checkbox" class="sr-only peer" {{ old('email_notifications', $subscription->email_notifications) ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                </div>
                            </label>
                        </div>

                        <div
                            class="flex items-center justify-between p-4 rounded-xl bg-background-light dark:bg-gray-800/50 border border-[#dce3e4] dark:border-gray-800 transition-all hover:border-primary/30">
                            <div class="flex items-center gap-4">
                                <div class="bg-primary/10 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-primary">event_repeat</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-[#121617] dark:text-white">Recursive Renewal
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Automatically renew subscription
                                        and extend validity.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input name="auto_renewal" type="checkbox" class="sr-only peer" {{ old('auto_renewal', $subscription->auto_renewal) ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                </div>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Footer Actions -->
                <div
                    class="bg-gray-50 dark:bg-slate-800/50 px-8 py-6 flex justify-end gap-3 border-t border-[#dce3e4] dark:border-slate-800">
                    <a href="{{ route('subscriptions.index') }}"
                        class="px-6 py-2.5 rounded-xl border border-[#dce3e4] dark:border-slate-700 text-[#121617] dark:text-white text-sm font-bold hover:bg-white dark:hover:bg-slate-800 transition-all">
                        Cancel Edits
                    </a>
                    <button type="submit"
                        class="px-8 py-2.5 rounded-xl bg-primary text-white text-sm font-black hover:brightness-110 shadow-lg shadow-primary/20 transition-all active:scale-95">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>