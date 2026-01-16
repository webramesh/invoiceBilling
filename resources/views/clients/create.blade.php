<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('clients.index') }}"
                class="p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors group">
                <span class="material-symbols-outlined text-gray-500 group-hover:text-primary">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl font-black text-[#121617] dark:text-white tracking-tight">Add New Client</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 font-medium">Register a new client and assign
                    their first service</p>
            </div>
        </div>
    </x-slot>

    <div class="p-8 max-w-5xl mx-auto">
        <form method="POST" action="{{ route('clients.store') }}" class="space-y-8">
            @csrf

            <!-- Default status to active -->
            <input type="hidden" name="status" value="active">

            <!-- Contact Information Section -->
            <section
                class="bg-white dark:bg-background-dark p-8 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center gap-3 mb-8 pb-4 border-b border-gray-100 dark:border-white/5">
                    <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">person</span>
                    <h3 class="text-lg font-black text-[#121617] dark:text-white">Client Contact Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Full
                            Name</label>
                        <input id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror"
                            placeholder="e.g. John Doe" type="text" />
                        @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Email
                            Address</label>
                        <input id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror"
                            placeholder="john@example.com" type="email" />
                        @error('email') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="whatsapp_number"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">WhatsApp Phone
                            Number</label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">+</span>
                            <input id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}"
                                class="w-full pl-8 pr-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary"
                                placeholder="1 555 123 4567" type="tel" />
                        </div>
                        @error('whatsapp_number') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}
                        </p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="company"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Business Name</label>
                        <input id="company" name="company" value="{{ old('company') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary"
                            placeholder="Acme Corporation" type="text" />
                        @error('company') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="phone"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Regular Phone
                            (Optional)</label>
                        <input id="phone" name="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary"
                            placeholder="Primary contact number" type="text" />
                        @error('phone') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <!-- Service Assignment Section -->
            <section
                class="bg-white dark:bg-background-dark p-8 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center gap-3 mb-8 pb-4 border-b border-gray-100 dark:border-white/5">
                    <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">dns</span>
                    <h3 class="text-lg font-black text-[#121617] dark:text-white">Service Assignment (First Service)
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="service_id"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Select
                            Service</label>
                        <select id="service_id" name="service_id"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary appearance-none">
                            <option value="">Choose a service...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} (Rs. {{ number_format($service->base_price) }})
                                </option>
                            @endforeach
                        </select>
                        @error('service_id') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="billing_cycle_id"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Billing Cycle</label>
                        <select id="billing_cycle_id" name="billing_cycle_id"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary appearance-none">
                            <option value="">Select cycle...</option>
                            @foreach($billingCycles as $cycle)
                                <option value="{{ $cycle->id }}" {{ old('billing_cycle_id') == $cycle->id ? 'selected' : '' }}>
                                    {{ $cycle->name }} ({{ $cycle->months }} Months)
                                </option>
                            @endforeach
                        </select>
                        @error('billing_cycle_id') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">
                        {{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="start_date"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Start Date</label>
                        <input id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary"
                            type="date" />
                        @error('start_date') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="price"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Service Price
                            (Rs.)</label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rs.</span>
                            <input id="price" name="price" value="{{ old('price') }}" step="0.01"
                                class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary"
                                placeholder="0.00" type="number" />
                        </div>
                        @error('price') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 p-4 bg-primary/5 rounded-xl border border-primary/10 flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary">info</span>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        <span class="font-bold text-primary">Billing Note:</span> Initial invoice will be generated
                        automatically based on the selected start date and billing cycle.
                    </p>
                </div>
            </section>

            <!-- Bottom Actions -->
            <div class="flex items-center justify-end gap-4 py-6">
                <a href="{{ route('clients.index') }}"
                    class="px-8 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition-colors">
                    Discard Draft
                </a>
                <button type="submit"
                    class="px-10 py-3 bg-primary text-white rounded-xl font-bold text-base hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    Save Client Profile
                </button>
            </div>
        </form>
    </div>
</x-app-layout>