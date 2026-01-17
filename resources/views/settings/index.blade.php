<x-app-layout>
    <div class="max-w-6xl mx-auto px-8">
        <header class="mb-8">
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
            <div class="flex gap-2 p-1.5 bg-gray-100 dark:bg-white/5 rounded-2xl w-fit">
                <button @click="activeTab = 'smtp'"
                    :class="activeTab === 'smtp' ? 'bg-white dark:bg-white/10 text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="px-8 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg"
                        :class="activeTab === 'smtp' ? 'font-fill' : ''">mail</span>
                    SMTP Setup
                </button>
                <button @click="activeTab = 'whatsapp'"
                    :class="activeTab === 'whatsapp' ? 'bg-white dark:bg-white/10 text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="px-8 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg"
                        :class="activeTab === 'whatsapp' ? 'font-fill' : ''">chat_bubble</span>
                    WhatsApp
                </button>
            </div>

            <!-- SMTP Settings Form -->
            <form action="{{ route('settings.update') }}" method="POST" x-show="activeTab === 'smtp'"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 gap-8">
                    <!-- SMTP Server Settings Card -->
                    <section class="card-premium">
                        <div class="card-header">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary font-fill text-xl">dns</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-[#121617] dark:text-white">SMTP Server Settings
                                    </h3>
                                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Gateway
                                        Configuration</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-x-6 gap-y-8">
                                <div class="form-group md:col-span-12">
                                    <label class="form-label">Mail Driver</label>
                                    <select name="mail_mailer" class="form-select">
                                        <option value="smtp" {{ ($settings['mail_mailer'] ?? '') == 'smtp' ? 'selected' : '' }}>SMTP (Recommended)</option>
                                        <option value="mailgun" {{ ($settings['mail_mailer'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                        <option value="sendmail" {{ ($settings['mail_mailer'] ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    </select>
                                </div>

                                <div class="form-group md:col-span-6">
                                    <label class="form-label">SMTP Host</label>
                                    <input name="mail_host" value="{{ $settings['mail_host'] ?? '' }}"
                                        class="form-input" placeholder="smtp.gmail.com" type="text" />
                                </div>
                                <div class="form-group md:col-span-3">
                                    <label class="form-label">Port</label>
                                    <input name="mail_port" value="{{ $settings['mail_port'] ?? '' }}"
                                        class="form-input" placeholder="587" type="text" />
                                </div>
                                <div class="form-group md:col-span-3">
                                    <label class="form-label">Encryption</label>
                                    <select name="mail_encryption" class="form-select">
                                        <option value="tls" {{ ($settings['mail_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="" {{ ($settings['mail_encryption'] ?? '') == '' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>

                                <div class="form-group md:col-span-6">
                                    <label class="form-label">Username</label>
                                    <input name="mail_username" value="{{ $settings['mail_username'] ?? '' }}"
                                        class="form-input" placeholder="user@company.com" type="text" />
                                </div>
                                <div class="form-group md:col-span-6" x-data="{ show: false }">
                                    <label class="form-label">Password</label>
                                    <div class="relative">
                                        <input name="mail_password" :type="show ? 'text' : 'password'"
                                            value="{{ $settings['mail_password'] ?? '' }}" class="form-input pr-12"
                                            placeholder="••••••••••••" />
                                        <button type="button" @click="show = !show"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors focus:outline-none">
                                            <span class="material-symbols-outlined text-[20px]"
                                                x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Sender Details Card -->
                    <section class="card-premium">
                        <div class="card-header">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                    <span
                                        class="material-symbols-outlined text-primary font-fill text-xl">alternate_email</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-[#121617] dark:text-white">Sender Identity</h3>
                                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Public Email
                                        Details</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div class="form-group">
                                    <label class="form-label">From Name</label>
                                    <input name="mail_from_name" value="{{ $settings['mail_from_name'] ?? '' }}"
                                        class="form-input" placeholder="Billing Department" type="text" />
                                    <p class="form-help">The name users will see in their inbox.</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">From Email</label>
                                    <input name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}"
                                        class="form-input" placeholder="no-reply@company.com" type="email" />
                                    <p class="form-help">The email address used for outgoing mail.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Action Bar -->
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6 card-premium p-6">
                        <div class="flex items-center gap-3 text-amber-600">
                            <span class="material-symbols-outlined font-fill">info</span>
                            <p class="text-xs font-bold leading-tight uppercase tracking-tight">System Notice: Changes
                                affect all automation immediately.</p>
                        </div>
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <button type="button" class="btn-secondary w-full md:w-auto">
                                <span class="material-symbols-outlined text-[18px]">send</span>
                                Send Test
                            </button>
                            <button type="submit" class="btn-primary w-full md:w-auto">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                Save Configuration
                            </button>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div
                        class="p-12 rounded-3xl bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-white/5 text-center flex flex-col items-center gap-6">
                        <div
                            class="w-16 h-16 rounded-full bg-white dark:bg-[#2a2e35] shadow-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-3xl font-fill">help_center</span>
                        </div>
                        <div class="max-w-xl">
                            <h4 class="text-xl font-black text-[#121617] dark:text-white mb-2">Need help with your SMTP
                                settings?</h4>
                            <p class="text-sm text-[#667f85] dark:text-[#a1b0b4] leading-relaxed font-medium">Most
                                providers like Google, Outlook, and SendGrid have specific security requirements. Check
                                our knowledge base for step-by-step guides.</p>
                        </div>
                        <a href="#"
                            class="text-primary text-sm font-black hover:opacity-80 flex items-center gap-2 group transition-opacity">
                            View Integration Guides
                            <span
                                class="material-symbols-outlined text-[18px] transition-transform group-hover:translate-x-1">open_in_new</span>
                        </a>
                    </div>
                </div>
            </form>

            <!-- WhatsApp Settings Form -->
            <div x-show="activeTab === 'whatsapp'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <section
                    class="bg-white dark:bg-[#1f2228] rounded-2xl p-16 text-center border border-dashed border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-primary text-4xl font-fill">chat_bubble</span>
                    </div>
                    <h2 class="text-2xl font-black text-[#121617] dark:text-white mb-2">WhatsApp Integration Paused</h2>
                    <p class="text-gray-500 dark:text-gray-400 font-medium max-w-md mx-auto mb-8">We're temporarily
                        bypassing WhatsApp configuration to focus on your email delivery setup. You can restore this
                        later.</p>
                    <button @click="activeTab = 'smtp'" class="btn-secondary mx-auto">
                        Back to SMTP setup
                    </button>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>