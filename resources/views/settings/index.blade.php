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

        @if(session('error'))
            <div
                class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-600 rounded-xl text-sm font-bold flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                {{ session('error') }}
            </div>
        @endif

        <div x-data="{ activeTab: 'smtp', showTestEmailModal: false }" class="space-y-8">
            <!-- Tabs Navigation -->
            <div class="flex gap-2 p-1.5 bg-gray-100 dark:bg-white/5 rounded-2xl w-fit">
                <button @click="activeTab = 'branding'"
                    :class="activeTab === 'branding' ? 'bg-white dark:bg-white/10 text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="px-8 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg"
                        :class="activeTab === 'branding' ? 'font-fill' : ''">palette</span>
                    Branding
                </button>
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

            <!-- Branding Settings Form -->
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" x-show="activeTab === 'branding'"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 gap-8">
                    <!-- Brand Identity Card -->
                    <section class="card-premium">
                        <div class="card-header">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary font-fill text-xl">palette</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-[#121617] dark:text-white">Brand Identity</h3>
                                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Visual Customization</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div class="form-group">
                                    <label class="form-label">Company Name</label>
                                    <input name="company_name" value="{{ $settings['company_name'] ?? '' }}"
                                        class="form-input" placeholder="My SaaS Company" type="text" />
                                    <p class="form-help">Appears in the dashboard header and emails.</p>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Primary Color</label>
                                    <div class="flex items-center gap-3">
                                        <input name="company_color" value="{{ $settings['company_color'] ?? '#2492a8' }}"
                                            class="h-10 w-20 rounded cursor-pointer border-0 p-0" type="color" />
                                        <input type="text" class="form-input flex-1" :value="$el.previousElementSibling.value" readonly> 
                                    </div>
                                    <p class="form-help">Main theme color for buttons and sidebar.</p>
                                </div>

                                <div class="form-group md:col-span-2">
                                    <label class="form-label">Company Logo</label>
                                    @if(isset($settings['company_logo']))
                                        <div class="mb-4 p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-dashed border-gray-200 dark:border-white/10 w-fit">
                                            <img src="{{ Storage::url($settings['company_logo']) }}" alt="Current Logo" class="h-12 w-auto object-contain">
                                        </div>
                                    @endif
                                    <input name="company_logo" type="file" accept="image/*" class="form-input file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"/>
                                    <p class="form-help">Upload a PNG or SVG logo (Max 2MB). Recommended height: 40px.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Save Branding
                        </button>
                    </div>
                </div>
            </form>

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
                            <button type="button" @click="showTestEmailModal = true" class="btn-secondary w-full md:w-auto">
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
            <form action="{{ route('settings.update') }}" method="POST" x-show="activeTab === 'whatsapp'"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 gap-8">
                    <!-- WhatsApp Configuration Card -->
                    <section class="card-premium">
                        <div class="card-header">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                    <span
                                        class="material-symbols-outlined text-primary font-fill text-xl">chat_bubble</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-[#121617] dark:text-white">WhatsApp Configuration
                                    </h3>
                                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Direct
                                        Communication</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 gap-8">
                                <div class="form-group">
                                    <label class="form-label">Message Template</label>
                                    <textarea name="whatsapp_template"
                                        class="form-input min-h-[150px] font-mono text-sm"
                                        placeholder="Hello {name}, your invoice {invoice_number} is ready...">{{ $settings['whatsapp_template'] ?? "Hello {name},\n\nYour invoice #{invoice_number} for {service_name} is ready.\n\nAmount: Rs. {amount}\nDue Date: {due_date}\n\nView Invoice: {invoice_url}\n\nThank you!" }}</textarea>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <span
                                            class="px-2 py-1 bg-gray-100 dark:bg-white/5 rounded text-[10px] font-bold text-gray-400 uppercase tracking-widest">{name}</span>
                                        <span
                                            class="px-2 py-1 bg-gray-100 dark:bg-white/5 rounded text-[10px] font-bold text-gray-400 uppercase tracking-widest">{invoice_number}</span>
                                        <span
                                            class="px-2 py-1 bg-gray-100 dark:bg-white/5 rounded text-[10px] font-bold text-gray-400 uppercase tracking-widest">{service_name}</span>
                                        <span
                                            class="px-2 py-1 bg-gray-100 dark:bg-white/5 rounded text-[10px] font-bold text-gray-400 uppercase tracking-widest">{amount}</span>
                                        <span
                                            class="px-2 py-1 bg-gray-100 dark:bg-white/5 rounded text-[10px] font-bold text-gray-400 uppercase tracking-widest">{due_date}</span>
                                        <span
                                            class="px-2 py-1 bg-gray-100 dark:bg-white/5 rounded text-[10px] font-bold text-gray-400 uppercase tracking-widest">{invoice_url}</span>
                                    </div>
                                    <p class="form-help mt-3">This message will be pre-filled when you click the
                                        WhatsApp button.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Hidden Bridge Settings (for advanced users) -->
                    <div x-data="{ showAdvanced: false }" class="space-y-4">
                        <button type="button" @click="showAdvanced = !showAdvanced"
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-primary transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm"
                                x-text="showAdvanced ? 'expand_less' : 'expand_more'"></span>
                            Advanced Bridge Settings (Optional)
                        </button>

                        <div x-show="showAdvanced">
                            <section class="card-premium">
                                <div class="card-body">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="form-group">
                                            <label class="form-label">API URL</label>
                                            <input name="whatsapp_api_url"
                                                value="{{ $settings['whatsapp_api_url'] ?? '' }}" class="form-input"
                                                placeholder="http://localhost:3001" type="text" />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">API Key</label>
                                            <input name="whatsapp_api_key"
                                                value="{{ $settings['whatsapp_api_key'] ?? '' }}" class="form-input"
                                                placeholder="Your API Secret" type="password" />
                                        </div>
                                    </div>
                                    <p class="form-help mt-4">Only needed if you want to use the automated background
                                        bridge.</p>
                                </div>
                            </section>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Save WhatsApp Settings
                        </button>
                    </div>
                </div>
            </form>
     
            <!-- Test Email Slide Panel -->
            <div x-show="showTestEmailModal" 
                 class="fixed inset-0 z-50 overflow-hidden" 
                 style="display: none;"
                 x-data="{ 
                     testEmail: '', 
                     sending: false, 
                     message: '', 
                     messageType: '',
                     async sendTestEmail() {
                         this.sending = true;
                         this.message = '';
                         
                         try {
                             const formData = new FormData();
                             formData.append('email', this.testEmail);
                             formData.append('_token', '{{ csrf_token() }}');
                             
                             const response = await fetch('{{ route('settings.test-email') }}', {
                                 method: 'POST',
                                 body: formData
                             });
                             
                             const data = await response.text();
                             
                             if (response.ok) {
                                 this.messageType = 'success';
                                 this.message = 'Test email sent successfully to ' + this.testEmail;
                                 this.testEmail = '';
                                 
                                 // Auto close after 3 seconds
                                 setTimeout(() => {
                                     showTestEmailModal = false;
                                     this.message = '';
                                 }, 3000);
                             } else {
                                 this.messageType = 'error';
                                 this.message = 'Failed to send test email. Please check your SMTP settings.';
                             }
                         } catch (error) {
                             this.messageType = 'error';
                             this.message = 'An error occurred: ' + error.message;
                         } finally {
                             this.sending = false;
                         }
                     }
                 }">
                
                <!-- Backdrop (blurred overlay) -->
                <div x-show="showTestEmailModal" 
                     x-transition:enter="transition-opacity ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900/30 dark:bg-black/50 backdrop-blur-sm" 
                     @click="showTestEmailModal = false">
                </div>

                <!-- Slide Panel -->
                <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="showTestEmailModal"
                         x-transition:enter="transform transition ease-out duration-500"
                         x-transition:enter-start="translate-x-full opacity-0"
                         x-transition:enter-end="translate-x-0 opacity-100"
                         x-transition:leave="transform transition ease-in duration-300"
                         x-transition:leave-start="translate-x-0 opacity-100"
                         x-transition:leave-end="translate-x-full opacity-0"
                         class="w-screen max-w-md">
                        
                        <div class="flex h-full flex-col bg-white dark:bg-[#1e2329] shadow-xl">
                            <!-- Header -->
                            <div class="bg-primary px-6 py-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-white text-xl">send</span>
                                        </div>
                                        <h2 class="text-xl font-bold text-white">Send Test Email</h2>
                                    </div>
                                    <button @click="showTestEmailModal = false" 
                                            class="text-white/80 hover:text-white transition-colors">
                                        <span class="material-symbols-outlined">close</span>
                                    </button>
                                </div>
                                <p class="mt-2 text-sm text-white/80">
                                    Verify your SMTP configuration by sending a test email
                                </p>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 overflow-y-auto px-6 py-6">
                                <!-- Success/Error Message -->
                                <div x-show="message" 
                                     x-transition
                                     :class="messageType === 'success' ? 'bg-green-500/10 border-green-500/20 text-green-600' : 'bg-red-500/10 border-red-500/20 text-red-600'"
                                     class="mb-4 p-4 border rounded-xl text-sm font-bold flex items-center gap-3">
                                    <span class="material-symbols-outlined" x-text="messageType === 'success' ? 'check_circle' : 'error'"></span>
                                    <span x-text="message"></span>
                                </div>

                                <form @submit.prevent="sendTestEmail" class="space-y-4">
                                    <div class="form-group">
                                        <label class="form-label">Recipient Email Address</label>
                                        <input type="email" 
                                               x-model="testEmail"
                                               class="form-input w-full" 
                                               placeholder="you@example.com" 
                                               required
                                               :disabled="sending">
                                        <p class="form-help mt-2">
                                            Enter the email address where you want to receive the test email
                                        </p>
                                    </div>

                                    <!-- Info Box -->
                                    <div class="p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                                        <div class="flex gap-3">
                                            <span class="material-symbols-outlined text-blue-600 text-xl">info</span>
                                            <div class="flex-1">
                                                <h4 class="text-sm font-bold text-blue-600 mb-1">What happens next?</h4>
                                                <p class="text-xs text-blue-600/80">
                                                    We'll send a simple test message to verify your SMTP settings are configured correctly. Check your inbox (and spam folder) for the test email.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Footer -->
                            <div class="border-t border-gray-200 dark:border-white/10 px-6 py-4 bg-gray-50 dark:bg-[#161a1e]">
                                <div class="flex gap-3">
                                    <button type="button" 
                                            @click="showTestEmailModal = false" 
                                            class="flex-1 btn-secondary">
                                        Cancel
                                    </button>
                                    <button type="button"
                                            @click="sendTestEmail"
                                            :disabled="sending || !testEmail"
                                            class="flex-1 btn-primary disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span x-show="!sending" class="material-symbols-outlined text-[18px]">send</span>
                                        <span x-show="sending" class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span>
                                        <span x-text="sending ? 'Sending...' : 'Send'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>