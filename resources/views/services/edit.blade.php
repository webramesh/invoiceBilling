<x-app-layout>
    <div class="flex-1 overflow-y-auto p-8 bg-background-light dark:bg-background-dark/50">
        <div class="max-w-[800px] mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold tracking-tight text-[#121617] dark:text-white">Edit Service</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Modify the configuration, pricing, and billing terms for <span class="text-primary font-black">{{ $service->name }}</span></p>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-background-dark rounded-xl shadow-sm border border-gray-200 dark:border-white/5 overflow-hidden">
                <form action="{{ route('services.update', $service) }}" method="POST" class="divide-y divide-gray-100 dark:divide-white/5">
                    @csrf
                    @method('PUT')
                    
                    <!-- General Info Section -->
                    <div class="p-8">
                        <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">info</span>
                            General Information
                        </h3>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Service Name</label>
                                <input name="name" type="text" value="{{ old('name', $service->name) }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-primary placeholder:text-gray-400 dark:text-white" 
                                    placeholder="e.g. Enterprise Managed Hosting"/>
                                @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Description</label>
                                <textarea name="description" rows="3"
                                    class="w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-primary placeholder:text-gray-400 dark:text-white" 
                                    placeholder="Provide a detailed description of what is included in this service...">{{ old('description', $service->description) }}</textarea>
                                @error('description') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Category</label>
                                    <select name="service_category_id" required
                                        class="w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-primary dark:text-white">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('service_category_id', $service->service_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_category_id') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Listing Status</label>
                                    <select name="status"
                                        class="w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-primary dark:text-white">
                                        <option value="active" {{ old('status', $service->status) == 'active' ? 'selected' : '' }}>Active (Visible)</option>
                                        <option value="inactive" {{ old('status', $service->status) == 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Tax Section -->
                    <div class="p-8">
                        <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">payments</span>
                            Pricing & Tax Configuration
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Base Price (Rs.)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 font-bold">Rs.</span>
                                    <input name="base_price" type="number" step="0.01" value="{{ old('base_price', $service->base_price) }}" required
                                        class="w-full pl-10 rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-primary dark:text-white" 
                                        placeholder="0.00"/>
                                </div>
                                <p class="text-xs text-gray-400 mt-2">This is the default price before any period-based discounts.</p>
                                @error('base_price') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Tax Status</label>
                                    <select name="tax_status"
                                        class="w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-primary dark:text-white">
                                        <option value="standard" {{ old('tax_status', $service->tax_status) == 'standard' ? 'selected' : '' }}>Standard Tax Rate</option>
                                        <option value="exempt" {{ old('tax_status', $service->tax_status) == 'exempt' ? 'selected' : '' }}>Tax Exempt</option>
                                        <option value="reduced" {{ old('tax_status', $service->tax_status) == 'reduced' ? 'selected' : '' }}>Reduced Rate</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Tax Rate (%)</label>
                                    <div class="relative">
                                        <input name="tax_rate" type="number" step="0.01" value="{{ old('tax_rate', $service->tax_rate) }}"
                                            class="w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-primary dark:text-white" 
                                            placeholder="0.00"/>
                                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 font-bold">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Cycles Section -->
                    <div class="p-8">
                        <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">update</span>
                            Allowed Billing Cycles
                        </h3>
                        
                        <div class="space-y-8">
                            @php
                                $opts = $service->billing_options ?? ['one_time' => [], 'recurring' => []];
                            @endphp
                            <!-- One-Time Group -->
                            <div>
                                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">payments</span>
                                    One-Time Payment Options
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                    @foreach($billingCycles as $cycle)
                                        @php $isChecked = in_array($cycle->id, $opts['one_time'] ?? []); @endphp
                                        <label class="flex items-center gap-3 p-4 border {{ $isChecked ? 'border-primary/50 bg-primary/5' : 'border-gray-200 dark:border-white/10' }} rounded-xl cursor-pointer hover:bg-primary/5 transition-all group">
                                            <input type="checkbox" name="billing_options[one_time][]" value="{{ $cycle->id }}" {{ $isChecked ? 'checked' : '' }}
                                                class="size-5 rounded text-primary focus:ring-primary border-gray-300 dark:border-white/20 dark:bg-white/5"/>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold dark:text-white">{{ $cycle->name }}</span>
                                                <span class="text-[10px] text-gray-500 font-bold uppercase">{{ $cycle->months }} Months</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Recurring Group -->
                            <div>
                                <p class="text-xs font-black text-primary uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">event_repeat</span>
                                    Recurring Billing Options
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                    @foreach($billingCycles as $cycle)
                                        @php $isChecked = in_array($cycle->id, $opts['recurring'] ?? []); @endphp
                                        <label class="flex items-center gap-3 p-4 border {{ $isChecked ? 'border-primary/50 bg-primary/5' : 'border-gray-200 dark:border-white/10' }} rounded-xl cursor-pointer hover:bg-primary/10 transition-all group">
                                            <input type="checkbox" name="billing_options[recurring][]" value="{{ $cycle->id }}" {{ $isChecked ? 'checked' : '' }}
                                                class="size-5 rounded text-primary focus:ring-primary border-gray-300 dark:border-white/20 dark:bg-white/5"/>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold dark:text-white">{{ $cycle->name }}</span>
                                                <span class="text-[10px] text-primary/70 font-bold uppercase">Renew Every {{ $cycle->months }}m</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="p-8 bg-gray-50 dark:bg-white/[0.02] flex items-center justify-between">
                        <button type="button" onclick="history.back()"
                            class="text-sm font-bold text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors uppercase tracking-wider">
                            Discard Changes
                        </button>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-400 group cursor-pointer hover:text-gray-200 transition-colors mr-4">
                                <input type="checkbox" name="is_draft" value="1" {{ $service->is_draft ? 'checked' : '' }}
                                    class="rounded bg-white/5 border-white/10 text-primary focus:ring-primary"/>
                                Save as Draft
                            </label>
                            <button type="submit" 
                                class="px-8 py-2.5 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 shadow-md shadow-primary/20 transition-all">
                                Update Service
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Helper Footer -->
            <div class="mt-8 flex items-center justify-center gap-8 py-4 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] dark:text-white">verified_user</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest dark:text-white">PCI Compliant</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] dark:text-white">security</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest dark:text-white">Secure Infrastructure</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] dark:text-white">cloud_done</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest dark:text-white">Auto-Scale Ready</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>