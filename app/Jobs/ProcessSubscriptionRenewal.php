<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Services\InvoiceService;
use App\Services\NotificationService;
use App\Services\MailConfigService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSubscriptionRenewal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subscriptionId;

    /**
     * Create a new job instance.
     *
     * @param int $subscriptionId
     */
    public function __construct(int $subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
    }

    /**
     * Execute the job.
     */
    public function handle(InvoiceService $invoiceService, NotificationService $notificationService): void
    {
        try {
            $subscription = Subscription::with('client.user', 'service', 'billingCycle')->find($this->subscriptionId);

            if (!$subscription) {
                Log::warning("Subscription #{$this->subscriptionId} not found during processing.");
                return;
            }

            // Get the tenant user who owns this client
            $user = $subscription->client->user; 
            
            // Configure Mail for this tenant (Critical for Queues)
            if ($user) {
                MailConfigService::configureMail($user);
            }

            // Generate Invoice
            $invoice = $invoiceService->generateForSubscription($subscription);

            // Send Notifications
            $channels = [];
            if ($subscription->email_notifications) {
                $channels[] = 'email';
            }
            
            // Check WhatsApp permission and preference
            if ($subscription->whatsapp_notifications && $user && $user->saasPlan && $user->saasPlan->has_whatsapp) {
                    $channels[] = 'whatsapp';
            }

            if (!empty($channels)) {
                $notificationService->sendInvoice($invoice, $channels);
            }

            Log::info("Processed subscription #{$subscription->id} for Client: {$subscription->client->name}");
        } catch (\Exception $e) {
            Log::error("Failed processing subscription #{$this->subscriptionId}: " . $e->getMessage());
            // Retry logic could be added here ($this->release(60))
            throw $e; // Re-throw to mark job as failed
        }
    }
}
