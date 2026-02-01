<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-3xl font-black text-[#121617] dark:text-white tracking-tight leading-none">Choose Your Plan</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-2 font-medium">Power up your billing experience with premium features</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border {{ Auth::user()->saas_plan_id == $plan->id ? 'border-accent-teal ring-2 ring-accent-teal/20' : 'border-slate-200 dark:border-slate-800' }} shadow-xl overflow-hidden flex flex-col transition-all hover:scale-[1.02]">
                        @if(Auth::user()->saas_plan_id == $plan->id)
                            <div class="bg-accent-teal text-white text-[10px] font-black uppercase tracking-widest py-2 text-center">
                                Your Current Plan
                            </div>
                        @endif
                        
                        <div class="p-8">
                            <h3 class="text-xl font-bold text-primary dark:text-white mb-2">{{ $plan->name }}</h3>
                            <div class="flex items-baseline gap-1 mb-6">
                                <span class="text-4xl font-black text-primary dark:text-white">Rs. {{ number_format($plan->price, 0) }}</span>
                                <span class="text-slate-500 text-sm font-bold">/ month</span>
                            </div>

                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                                    <span class="material-symbols-outlined text-accent-teal text-lg">check_circle</span>
                                    {{ $plan->max_clients == -1 ? 'Unlimited' : $plan->max_clients }} Clients
                                </li>
                                <li class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                                    <span class="material-symbols-outlined text-accent-teal text-lg">check_circle</span>
                                    {{ $plan->max_invoices_per_month == -1 ? 'Unlimited' : $plan->max_invoices_per_month }} Invoices / month
                                </li>
                                <li class="flex items-center gap-3 text-sm {{ $plan->has_whatsapp ? 'text-slate-600 dark:text-slate-400' : 'text-slate-400 line-through opacity-50' }}">
                                    <span class="material-symbols-outlined {{ $plan->has_whatsapp ? 'text-accent-teal' : 'text-slate-300' }} text-lg">
                                        {{ $plan->has_whatsapp ? 'check_circle' : 'cancel' }}
                                    </span>
                                    WhatsApp Integration
                                </li>
                                <li class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                                    <span class="material-symbols-outlined text-accent-teal text-lg">check_circle</span>
                                    Automated Reminders
                                </li>
                            </ul>

                            @if(Auth::user()->saas_plan_id != $plan->id)
                                <form action="{{ route('pricing.subscribe', $plan) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-primary hover:bg-primary-light text-white font-bold py-4 px-6 rounded-xl transition-all shadow-lg shadow-primary/20">
                                        Upgrade Now
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-4 px-6 rounded-xl border border-slate-100 dark:border-slate-800 text-slate-400 text-sm font-bold italic">
                                    Currently Active
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 bg-teal-50 dark:bg-teal-950/20 rounded-2xl p-8 border border-teal-100 dark:border-teal-900/50 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h4 class="text-lg font-bold text-accent-teal mb-1">Need a custom enterprise solution?</h4>
                    <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">We offer tailored packages for large scale operations and white-labeling.</p>
                </div>
                <button class="bg-white dark:bg-slate-900 text-primary dark:text-white hover:bg-slate-50 border border-slate-200 dark:border-slate-800 font-bold py-3 px-8 rounded-xl transition-all shadow-sm">
                    Contact Sales
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
