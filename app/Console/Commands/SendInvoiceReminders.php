<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\Setting;
use App\Jobs\SendReminderEmail;
use Illuminate\Support\Facades\DB;

class SendInvoiceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice-reminders:send {--limit=100 : Maximum number of reminders to queue per run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send invoice reminders based on configured schedules (optimized for shared hosting)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = microtime(true);
        $limit = (int) $this->option('limit');
        
        // Get all unique users (tenants) with active subscriptions
        $userIds = Subscription::where('status', 'active')->distinct()->pluck('user_id');
        
        $processedCount = 0;
        
        foreach ($userIds as $userId) {
            // Check global limit
            if ($processedCount >= $limit) {
                $this->info("Reached limit of {$limit} reminders.");
                break;
            }

            // Get settings for this tenant (no global scope in console, so where clause works)
            $settings = Setting::where('user_id', $userId)->pluck('value', 'key');
            
            $reminders = [
                [
                    'days' => (int) ($settings['reminder_1_days'] ?? 30),
                    'enabled' => ($settings['reminder_1_enabled'] ?? '1') === '1',
                    'type' => 'reminder_1',
                    'label' => 'First Reminder'
                ],
                [
                    'days' => (int) ($settings['reminder_2_days'] ?? 15),
                    'enabled' => ($settings['reminder_2_enabled'] ?? '1') === '1',
                    'type' => 'reminder_2',
                    'label' => 'Second Reminder'
                ],
                [
                    'days' => (int) ($settings['reminder_3_days'] ?? 5),
                    'enabled' => ($settings['reminder_3_enabled'] ?? '1') === '1',
                    'type' => 'reminder_3',
                    'label' => 'Third Reminder'
                ],
                [
                    'days' => (int) ($settings['reminder_final_days'] ?? 1),
                    'enabled' => ($settings['reminder_final_enabled'] ?? '1') === '1',
                    'type' => 'reminder_final',
                    'label' => 'Final Reminder'
                ],
            ];
            
            foreach ($reminders as $reminder) {
                if (!$reminder['enabled']) {
                    continue;
                }

                if ($processedCount >= $limit) break;
                
                $targetDate = now()->addDays($reminder['days'])->format('Y-m-d');
                
                // Efficient query with chunking
                $query = Subscription::where('user_id', $userId) // Filter by tenant
                    ->where('status', 'active')
                    ->where('reminders_enabled', true)
                    ->whereDate('next_billing_date', $targetDate)
                    ->with(['client', 'service'])
                    ->limit($limit - $processedCount);
                
                // Check if reminder already sent today
                $query->where(function ($q) use ($reminder, $targetDate) {
                    $q->whereNull('last_reminders_sent')
                      ->orWhereRaw("JSON_EXTRACT(last_reminders_sent, '$.{$reminder['type']}') IS NULL")
                      ->orWhereRaw("JSON_EXTRACT(last_reminders_sent, '$.{$reminder['type']}') != ?", [$targetDate]);
                });
                
                // Process in small chunks
                $query->chunk(10, function ($subscriptions) use ($reminder, &$processedCount) {
                    foreach ($subscriptions as $subscription) {
                        if (!$subscription->client || !$subscription->client->email) {
                            continue;
                        }
                        
                        SendReminderEmail::dispatch($subscription, $reminder['type'], $reminder['days'])
                            ->onQueue('reminders');
                        
                        $processedCount++;
                    }
                });
            }
        }
        
        $executionTime = round(microtime(true) - $startTime, 2);
        $memoryUsage = round(memory_get_peak_usage(true) / 1024 / 1024, 2);
        
        $this->info("Processed reminders for " . count($userIds) . " tenants.");
        $this->info("Total queued: {$processedCount}");
        
        \Log::channel('daily')->info('Invoice reminders processed', [
            'tenants' => count($userIds),
            'queued' => $processedCount,
            'execution_time' => $executionTime,
            'memory_usage' => $memoryUsage . 'MB',
        ]);
        
        return Command::SUCCESS;
    }
}
