<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Cron Status Dashboard</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status Card -->
                <div class="bg-white dark:bg-[#161a1e] overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-white/5">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold flex items-center gap-2">
                                <span class="material-symbols-outlined">monitor_heart</span>
                                System Status
                            </h2>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $isRunning ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $isRunning ? 'Running' : 'Stopped' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center {{ $isRunning ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500' }}">
                                <span class="material-symbols-outlined text-4xl">{{ $isRunning ? 'check_circle' : 'error' }}</span>
                            </div>
                            <div>
                                <p class="text-xl font-bold {{ $isRunning ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $isRunning ? 'Cron is Active' : 'Cron is Inactive' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Last heartbeat: {{ $lastRun ? $lastRun->diffForHumans() : 'Never detected' }}
                                </p>
                            </div>
                        </div>
                        
                        @if(!$isRunning)
                        <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-100 dark:border-red-900/30">
                            <p class="text-sm text-red-600 dark:text-red-400 font-bold mb-1">Action Required</p>
                            <p class="text-xs text-red-500 dark:text-red-400/80">
                                The cron job has not run recently. Please check your server configuration and ensure the cron command is added to your crontab.
                            </p>
                        </div>
                        @else
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-100 dark:border-green-900/30">
                            <p class="text-sm text-green-600 dark:text-green-400 font-bold mb-1">All Systems Operational</p>
                            <p class="text-xs text-green-500 dark:text-green-400/80">
                                Creating invoices, sending reminders, and processing items are running on schedule.
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Command Card -->
                <div class="bg-white dark:bg-[#161a1e] overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-white/5">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined">terminal</span>
                            Server Configuration
                        </h2>
                        <p class="text-sm text-gray-500 mb-4">Add the following command to your server's crontab file to run every minute:</p>
                        
                        <div class="relative group">
                            <div class="bg-gray-100 dark:bg-black/30 p-4 rounded-lg font-mono text-xs break-all border border-gray-200 dark:border-white/10 select-all">
                                {{ $cronCommand }}
                            </div>
                            <button onclick="navigator.clipboard.writeText('{{ addslashes($cronCommand) }}'); this.innerText = 'Copied!'; setTimeout(() => this.innerText = 'Copy', 2000);" 
                                class="absolute top-2 right-2 px-2 py-1 bg-white dark:bg-white/10 rounded text-xs font-bold text-gray-500 hover:text-primary shadow-sm border border-gray-200 dark:border-white/10 transition-all">
                                Copy
                            </button>
                        </div>
                        
                        <div class="mt-6 space-y-3">
                            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Run Frequency</h3>
                            <div class="flex items-center gap-3">
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-mono">* * * * *</span>
                                <span class="text-sm text-gray-500">Run every minute (Recommended)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity Log (Placeholder for future iteration) -->
            <!-- We could show the last few lines of the log file here -->
        </div>
    </div>
</x-app-layout>
