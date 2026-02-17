<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Subscription;
use App\Models\NotificationLog;
use App\Mail\ReminderMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;
    public $backoff = [60, 300, 900]; // Retry after 1min, 5min, 15min
    
    protected $subscription;
    protected $reminderType;
    protected $daysUntilDue;

    /**
     * Create a new job instance.
     */
    public function __construct(Subscription $subscription, string $reminderType, int $daysUntilDue)
    {
        $this->subscription = $subscription;
        $this->reminderType = $reminderType;
        $this->daysUntilDue = $daysUntilDue;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Reload subscription with relationships to ensure fresh data
            $subscription = Subscription::with(['client', 'service', 'billingCycle', 'items'])
                ->find($this->subscription->id);
            
            if (!$subscription) {
                Log::warning("Subscription {$this->subscription->id} not found, skipping reminder");
                return;
            }
            
            // Double-check subscription is still active and reminders enabled
            if ($subscription->status !== 'active' || !$subscription->reminders_enabled) {
                Log::info("Subscription {$subscription->id} no longer active or reminders disabled, skipping");
                return;
            }
            
            // Check if client has email
            if (!$subscription->client || !$subscription->client->email) {
                Log::warning("Subscription {$subscription->id} has no client email, skipping");
                return;
            }
            
            // Send email
            Mail::to($subscription->client->email)
                ->send(new ReminderMailable($subscription, $this->reminderType, $this->daysUntilDue));
            
            // Update last sent reminders
            $lastReminders = $subscription->last_reminders_sent ?? [];
            $lastReminders[$this->reminderType] = now()->format('Y-m-d');
            $subscription->update(['last_reminders_sent' => $lastReminders]);
            
            // Log success
            NotificationLog::create([
                'user_id' => $subscription->user_id,
                'subscription_id' => $subscription->id,
                'type' => $this->reminderType,
                'channel' => 'email',
                'status' => 'sent',
                'sent_at' => now(),
            ]);
            
            Log::info("Reminder sent successfully", [
                'subscription_id' => $subscription->id,
                'client' => $subscription->client->name,
                'type' => $this->reminderType,
                'days_until_due' => $this->daysUntilDue,
            ]);
            
        } catch (\Exception $e) {
            // Log failure
            NotificationLog::create([
                'user_id' => $this->subscription->user_id ?? null,
                'subscription_id' => $this->subscription->id,
                'type' => $this->reminderType,
                'channel' => 'email',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            
            Log::error("Reminder failed", [
                'subscription_id' => $this->subscription->id,
                'type' => $this->reminderType,
                'error' => $e->getMessage(),
            ]);
            
            // Re-throw to trigger retry
            throw $e;
        }
    }
    
    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Reminder job failed permanently", [
            'subscription_id' => $this->subscription->id,
            'type' => $this->reminderType,
            'error' => $exception->getMessage(),
        ]);
    }
}
