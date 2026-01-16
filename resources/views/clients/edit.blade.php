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

    <div class="p-8 max-w-5xl mx-auto">
        <form method="POST" action="{{ route('clients.update', $client) }}" class="space-y-8">
            @csrf
            @method('PUT')

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