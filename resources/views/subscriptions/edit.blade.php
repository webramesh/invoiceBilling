<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Subscription') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('subscriptions.update', $subscription) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Client -->
                            <div>
                                <x-input-label for="client_id" :value="__('Client')" />
                                <select id="client_id" name="client_id"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id', $subscription->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                            </div>

                            <!-- Service -->
                            <div>
                                <x-input-label for="service_id" :value="__('Service')" />
                                <select id="service_id" name="service_id"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" {{ old('service_id', $subscription->service_id) == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
                            </div>

                            <!-- Billing Cycle -->
                            <div>
                                <x-input-label for="billing_cycle_id" :value="__('Billing Cycle')" />
                                <select id="billing_cycle_id" name="billing_cycle_id"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required>
                                    @foreach($billingCycles as $cycle)
                                        <option value="{{ $cycle->id }}" {{ old('billing_cycle_id', $subscription->billing_cycle_id) == $cycle->id ? 'selected' : '' }}>
                                            {{ $cycle->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('billing_cycle_id')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required>
                                    <option value="active" {{ old('status', $subscription->status) === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="suspended" {{ old('status', $subscription->status) === 'suspended' ? 'selected' : '' }}>{{ __('Suspended') }}</option>
                                    <option value="cancelled" {{ old('status', $subscription->status) === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Start Date -->
                            <div>
                                <x-input-label for="start_date" :value="__('Start Date')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date"
                                    :value="old('start_date', $subscription->start_date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>

                            <!-- Next Billing Date -->
                            <div>
                                <x-input-label for="next_billing_date" :value="__('Next Billing Date')" />
                                <x-text-input id="next_billing_date" class="block mt-1 w-full" type="date"
                                    name="next_billing_date" :value="old('next_billing_date', $subscription->next_billing_date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('next_billing_date')" class="mt-2" />
                            </div>

                            <!-- Price -->
                            <div>
                                <x-input-label for="price" :value="__('Price')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01"
                                    name="price" :value="old('price', $subscription->price)" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <!-- Auto Renewal -->
                            <div class="flex items-center mt-6">
                                <label for="auto_renewal" class="inline-flex items-center">
                                    <input id="auto_renewal" type="checkbox"
                                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                        name="auto_renewal" {{ old('auto_renewal', $subscription->auto_renewal) ? 'checked' : '' }}>
                                    <span
                                        class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Enable Auto-Renewal') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('subscriptions.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update Subscription') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>