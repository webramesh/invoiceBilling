<x-app-layout>
    <div class="max-w-4xl mx-auto px-8 py-12">
        <header class="mb-10">
            <h1 class="text-[#121617] dark:text-white text-4xl font-black leading-tight tracking-tight">System Settings
            </h1>
            <p class="text-[#667f85] dark:text-[#a1b0b4] text-lg mt-2 font-normal">Configure your communication channels
                and system behavior.</p>
        </header>

        @if(session('success'))
            <div
                class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-600 rounded-xl text-sm font-bold flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div x-data="{ activeTab: 'smtp' }" class="space-y-8">
            <!-- Tabs Navigation -->
            <div class="flex gap-2 p-1 bg-gray-100 dark:bg-white/5 rounded-xl w-fit">
                <button @click="activeTab = 'smtp'"
                    :class="activeTab === 'smtp' ? 'bg-white dark:bg-white/10 text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">mail</span>
                    SMTP Setup
                </button>
                <button @click="activeTab = 'whatsapp'"
                    :class="activeTab === 'whatsapp' ? 'bg-white dark:bg-white/10 text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">chat_bubble</span>
                    WhatsApp Service
                </button>
            </div>

            <!-- SMTP Settings Form -->
            <section x-show="activeTab === 'smtp'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="bg-white dark:bg-[#1f2228] rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.06)] border border-gray-100 dark:border-white/5 overflow-hidden">
                <div class="p-8 border-b border-gray-100 dark:border-white/5">
                    <h2 class="text-xl font-bold text-[#121617] dark:text-white">Mail Server Configuration</h2>
                    <p class="text-sm text-gray-500 mt-1">Configure your outgoing mail server for invoices and
                        notifications.</p>
                </div>
                <form action="{{ route('settings.update') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Mail Driver</label>
                            <select name="mail_mailer"
                                class="w-full h-12 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white">
                                <option value="smtp" {{ ($settings['mail_mailer'] ?? '') == 'smtp' ? 'selected' : '' }}>
                                    SMTP</option>
                                <option value="mailgun" {{ ($settings['mail_mailer'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                <option value="sendmail" {{ ($settings['mail_mailer'] ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Mail Host</label>
                            <input name="mail_host" type="text" value="{{ $settings['mail_host'] ?? '' }}"
                                placeholder="smtp.mailtrap.io"
                                class="w-full h-12 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Mail Port</label>
                            <input name="mail_port" type="text" value="{{ $settings['mail_port'] ?? '' }}"
                                placeholder="2525"
                                class="w-full h-12 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Encryption</label>
                            <select name="mail_encryption"
                                class="w-full h-12 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white">
                                <option value="tls" {{ ($settings['mail_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>
                                    TLS</option>
                                <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>
                                    SSL</option>
                                <option value="" {{ ($settings['mail_encryption'] ?? '') == '' ? 'selected' : '' }}>None
                                </option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Username</label>
                            <input name="mail_username" type="text" value="{{ $settings['mail_username'] ?? '' }}"
                                class="w-full h-12 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Password</label>
                            <input name="mail_password" type="password" value="{{ $settings['mail_password'] ?? '' }}"
                                class="w-full h-12 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">From
                                Address</label>
                            <input name="mail_from_address" type="email"
                                value="{{ $settings['mail_from_address'] ?? '' }}" placeholder="noreply@example.com"
                                class="w-full h-12 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">From Name</label>
                            <input name="mail_from_name" type="text" value="{{ $settings['mail_from_name'] ?? '' }}"
                                placeholder="Billing System"
                                class="w-full h-12 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white" />
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end">
                        <button type="submit"
                            class="px-8 py-3 bg-primary text-white rounded-xl text-sm font-bold hover:bg-primary/90 transition-all shadow-sm">Save
                            SMTP Configuration</button>
                    </div>
                </form>
            </section>

            <!-- WhatsApp Settings Form -->
            <section x-show="activeTab === 'whatsapp'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="bg-white dark:bg-[#1f2228] rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.06)] border border-gray-100 dark:border-white/5 overflow-hidden">
                <div class="p-8 border-b border-gray-100 dark:border-white/5">
                    <h2 class="text-xl font-bold text-[#121617] dark:text-white">Free WhatsApp Bridge</h2>
                    <p class="text-sm text-gray-500 mt-1">Connect your locally hosted Node.js WhatsApp bridge for zero-cost messaging.</p>
                </div>
                <form action="{{ route('settings.update') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="space-y-6">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Self-Hosted URL (Bridge URL)</label>
                            <input name="whatsapp_api_url" type="url" value="{{ $settings['whatsapp_api_url'] ?? '' }}" placeholder="http://localhost:3000/api" class="w-full h-12 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white"/>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Security Token (Optional)</label>
                            <input name="whatsapp_api_key" type="password" value="{{ $settings['whatsapp_api_key'] ?? '' }}" placeholder="Your bridge security token" class="w-full h-12 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-800 px-4 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all dark:text-white"/>
                        </div>
                    </div>
                    <div class="p-6 bg-green-500/5 border border-green-500/10 rounded-2xl flex items-start gap-4">
                        <span class="material-symbols-outlined text-green-600 mt-0.5">verified_user</span>
                        <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                            <p class="font-bold text-green-600 uppercase tracking-tighter">Zero-Cost Setup</p>
                            <p>This system uses your own linked device via a Node.js bridge. There are no per-message fees.</p>
                            <p>Once connected, you can send unlimited invoices and reminders through your personal or business WhatsApp account.</p>
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-primary text-white rounded-xl text-sm font-bold hover:bg-primary/90 transition-all shadow-sm">Save Bridge Configuration</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>