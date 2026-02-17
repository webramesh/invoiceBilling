<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('clients.index') }}"
                class="p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors group">
                <span class="material-symbols-outlined text-gray-500 group-hover:text-primary">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl font-black text-[#121617] dark:text-white tracking-tight">Edit Client</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 font-medium">Update profile details for <span
                        class="text-primary font-bold">{{ $client->name }}</span></p>
            </div>
        </div>
    </x-slot>

    <div class="p-8 max-w-5xl mx-auto" x-data="{ tab: 'contact' }">
        <!-- Tabs Navigation -->
        <div class="flex items-center gap-6 mb-8 border-b border-gray-200 dark:border-white/10">
            <button @click="tab = 'contact'" 
                :class="tab === 'contact' ? 'text-primary border-primary' : 'text-gray-500 border-transparent hover:text-gray-700 dark:hover:text-white'"
                class="pb-4 px-2 text-sm font-bold uppercase tracking-widest border-b-2 transition-all">
                Client Contact Information
            </button>
            <button @click="tab = 'services'" 
                :class="tab === 'services' ? 'text-primary border-primary' : 'text-gray-500 border-transparent hover:text-gray-700 dark:hover:text-white'"
                class="pb-4 px-2 text-sm font-bold uppercase tracking-widest border-b-2 transition-all">
                Services
            </button>
        </div>

        <form method="POST" action="{{ route('clients.update', $client) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Tab 1: Contact Information -->
            <div x-show="tab === 'contact'" class="space-y-8">
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
                            <input id="name" name="name" value="{{ old('name', $client->name) }}" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror"
                                placeholder="e.g. John Doe" type="text" />
                            @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Email
                                Address</label>
                            <input id="email" name="email" value="{{ old('email', $client->email) }}" required
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
                                <input id="whatsapp_number" name="whatsapp_number"
                                    value="{{ old('whatsapp_number', $client->whatsapp_number) }}"
                                    class="w-full pl-8 pr-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary"
                                    placeholder="1 555 123 4567" type="tel" />
                            </div>
                            @error('whatsapp_number') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}
                            </p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="company"
                                class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Business Name</label>
                            <input id="company" name="company" value="{{ old('company', $client->company) }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary"
                                placeholder="Acme Corporation" type="text" />
                            @error('company') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label for="phone"
                                class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Regular Phone
                                (Optional)</label>
                            <input id="phone" name="phone" value="{{ old('phone', $client->phone) }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary"
                                placeholder="Primary contact number" type="text" />
                            @error('phone') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label for="address" class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Address</label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary"
                                placeholder="Street, City, State, ZIP">{{ old('address', $client->address) }}</textarea>
                            @error('address') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                <!-- Status Section -->
                <section
                    class="bg-white dark:bg-background-dark p-8 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="flex items-center gap-3 mb-8 pb-4 border-b border-gray-100 dark:border-white/5">
                        <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">shield</span>
                        <h3 class="text-lg font-black text-[#121617] dark:text-white">Account Status</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="status"
                                class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Client Lifecycle
                                Status</label>
                            <select id="status" name="status" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary appearance-none">
                                <option value="active" {{ old('status', $client->status) === 'active' ? 'selected' : '' }}>
                                    Active (Full Service)</option>
                                <option value="inactive" {{ old('status', $client->status) === 'inactive' ? 'selected' : '' }}>Inactive (Archived)</option>
                            </select>
                            @error('status') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>
            </div>

            <!-- Tab 2: Services -->
            <div x-show="tab === 'services'" class="space-y-8" style="display: none;">
                <!-- Manage Existing Services Section -->
                @if($client->subscriptions->count() > 0)
                <section class="bg-white dark:bg-background-dark p-8 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="flex items-center gap-3 mb-8 pb-4 border-b border-gray-100 dark:border-white/5">
                        <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">manage_history</span>
                        <h3 class="text-lg font-black text-[#121617] dark:text-white">Manage Existing Services</h3>
                    </div>

                    <div class="space-y-6">
                        @foreach($client->subscriptions as $subscription)
                            <div class="bg-gray-50 dark:bg-white/5 p-6 rounded-xl border border-gray-200 dark:border-white/10 relative">
                                <input type="hidden" name="subscriptions[{{ $subscription->id }}][id]" value="{{ $subscription->id }}">
                                
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h4 class="font-bold text-[#121617] dark:text-white text-lg">{{ $subscription->service->name }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Started: {{ $subscription->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <span class="bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide">
                                        {{ $subscription->service->category->name ?? 'Service' }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" x-data="{ price: {{ $subscription->price ?? 0 }}, quantity: {{ $subscription->quantity ?? 1 }} }">
                                    <!-- Service Given / Identifier (Full Width) -->
                                    <div class="space-y-1 lg:col-span-3">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Service Given (Identifier)</label>
                                        <input type="text" name="subscriptions[{{ $subscription->id }}][service_alias]" value="{{ $subscription->service_alias }}"
                                            placeholder="e.g. Website Hosting - example.com"
                                            class="w-full px-3 py-2 bg-white dark:bg-background-dark border-gray-200 dark:border-white/10 rounded-lg text-xs font-medium focus:ring-primary focus:border-primary">
                                    </div>

                                    <!-- Status -->
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Status</label>
                                        <select name="subscriptions[{{ $subscription->id }}][status]"
                                            class="w-full px-3 py-2 bg-white dark:bg-background-dark border-gray-200 dark:border-white/10 rounded-lg text-xs font-medium focus:ring-primary focus:border-primary appearance-none">
                                            <option value="active" {{ $subscription->status === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="suspended" {{ $subscription->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                            <option value="cancelled" {{ $subscription->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            <option value="completed" {{ $subscription->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </div>

                                    <!-- Billing Cycle -->
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Billing Cycle</label>
                                        <select name="subscriptions[{{ $subscription->id }}][billing_cycle_id]"
                                            class="w-full px-3 py-2 bg-white dark:bg-background-dark border-gray-200 dark:border-white/10 rounded-lg text-xs font-medium focus:ring-primary focus:border-primary appearance-none">
                                            @foreach($billingCycles as $cycle)
                                                <option value="{{ $cycle->id }}" {{ $subscription->billing_cycle_id == $cycle->id ? 'selected' : '' }}>
                                                    {{ $cycle->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Next Billing -->
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Next Billing</label>
                                        <input type="date" name="subscriptions[{{ $subscription->id }}][next_billing_date]" value="{{ $subscription->next_billing_date ? $subscription->next_billing_date->format('Y-m-d') : '' }}"
                                            class="w-full px-3 py-2 bg-white dark:bg-background-dark border-gray-200 dark:border-white/10 rounded-lg text-xs font-medium focus:ring-primary focus:border-primary">
                                    </div>

                                    <!-- Unit Price -->
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Unit Price (Rs.)</label>
                                        <input type="number" step="0.01" name="subscriptions[{{ $subscription->id }}][price]" x-model="price"
                                            class="w-full px-3 py-2 bg-white dark:bg-background-dark border-gray-200 dark:border-white/10 rounded-lg text-xs font-medium focus:ring-primary focus:border-primary">
                                    </div>

                                    <!-- Quantity -->
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Qty</label>
                                        <input type="number" min="1" step="1" name="subscriptions[{{ $subscription->id }}][quantity]" x-model="quantity"
                                            class="w-full px-3 py-2 bg-white dark:bg-background-dark border-gray-200 dark:border-white/10 rounded-lg text-xs font-medium focus:ring-primary focus:border-primary">
                                    </div>

                                    <!-- Total Calculation -->
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Total Amount</label>
                                        <div class="w-full px-3 py-2 bg-gray-100 dark:bg-white/10 border border-gray-200 dark:border-white/10 rounded-lg text-xs font-bold text-[#121617] dark:text-white flex items-center">
                                            Rs. <span x-text="(price * quantity).toFixed(2)"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- Add New Service Section -->
                <section class="bg-white dark:bg-background-dark p-8 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="flex items-center gap-3 mb-8 pb-4 border-b border-gray-100 dark:border-white/5">
                        <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">add_circle</span>
                        <h3 class="text-lg font-black text-[#121617] dark:text-white">Add New Service Assignment</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ newPrice: '{{ old('new_price') }}', newQuantity: {{ old('new_quantity', 1) }} }">
                        <div class="space-y-2">
                            <label for="new_service_id" class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Select Service</label>
                            <select id="new_service_id" name="new_service_id"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary appearance-none">
                                <option value="">Choose a service...</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('new_service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} (Rs. {{ number_format($service->base_price) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('new_service_id') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="new_service_alias" class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Service Given (Identifier / Name)</label>
                            <input id="new_service_alias" name="new_service_alias" value="{{ old('new_service_alias') }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary"
                                placeholder="e.g. Website Hosting - example.com" type="text" />
                            @error('new_service_alias') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="new_billing_cycle_id" class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Billing Cycle</label>
                            <select id="new_billing_cycle_id" name="new_billing_cycle_id"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary appearance-none">
                                <option value="">Select cycle...</option>
                                @foreach($billingCycles as $cycle)
                                    <option value="{{ $cycle->id }}" {{ old('new_billing_cycle_id') == $cycle->id ? 'selected' : '' }}>
                                        {{ $cycle->name }} ({{ $cycle->months }} Months)
                                    </option>
                                @endforeach
                            </select>
                            @error('new_billing_cycle_id') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="new_start_date" class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Start Date</label>
                            <input id="new_start_date" name="new_start_date" value="{{ old('new_start_date', date('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary"
                                type="date" />
                            @error('new_start_date') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="new_price" class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Unit Price (Rs.)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rs.</span>
                                <input id="new_price" name="new_price" step="0.01" x-model="newPrice"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary"
                                    placeholder="auto" type="number" />
                            </div>
                            @error('new_price') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="new_quantity" class="text-xs font-bold text-gray-400 uppercase tracking-widest px-1">Quantity</label>
                            <input id="new_quantity" name="new_quantity" min="1" step="1" x-model="newQuantity"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-primary focus:border-primary"
                                type="number" />
                            @error('new_quantity') <p class="text-red-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Total Calculation -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Total Amount</label>
                            <div class="w-full px-4 py-3 bg-gray-100 dark:bg-white/10 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-bold text-[#121617] dark:text-white flex items-center">
                                Rs. <span x-text="(newPrice && newQuantity ? (newPrice * newQuantity).toFixed(2) : '0.00')"></span>
                            </div>
                        </div>


                    </div>
                </section>
            </div>

            <!-- Bottom Actions -->
            <div class="flex items-center justify-end gap-4 py-6">
                <a href="{{ route('clients.index') }}"
                    class="px-8 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition-colors">
                    Discard Changes
                </a>
                <button type="submit"
                    class="px-10 py-3 bg-primary text-white rounded-xl font-bold text-base hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    Update Client Profile
                </button>
            </div>
        </form>
    </div>
</x-app-layout>