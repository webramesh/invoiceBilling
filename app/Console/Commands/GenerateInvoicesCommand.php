<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GenerateInvoicesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate invoices for due subscriptions';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\InvoiceService $invoiceService, \App\Services\NotificationService $notificationService)
    {
        $subscriptions = Subscription::with(['client.user', 'service', 'billingCycle'])
            ->where('status', 'active')
            ->where('next_billing_date', '<=', now()->toDateString())
            ->get();

        $this->info("Found " . $subscriptions->count() . " due subscriptions.");

        foreach ($subscriptions as $subscription) {
            try {
                // Get the tenant user who owns this client
                $user = $subscription->client->user; 
                
                // Configure Mail for this tenant
                if ($user) {
                    \App\Services\MailConfigService::configureMail($user);
                }

                // Generate Invoice
                $invoice = $invoiceService->generateForSubscription($subscription);

                // Send Notifications
                $channels = [];
                if ($subscription->email_notifications) {
                    $channels[] = 'email';
                }
                
                // Check WhatsApp permission and preference
                // Note: user relationship might be null if client was created without scoped user (shouldn't happen with traits)
                if ($subscription->whatsapp_notifications && $user && $user->saasPlan && $user->saasPlan->has_whatsapp) {
                     $channels[] = 'whatsapp';
                }

                if (!empty($channels)) {
                    $notificationService->sendInvoice($invoice, $channels);
                }

                $this->info("Processed subscription #{$subscription->id} for Client: {$subscription->client->name}");
            } catch (\Exception $e) {
                $this->error("Failed processing subscription #{$subscription->id}: " . $e->getMessage());
                \Log::error("Invoice Generation Failed for Subscription #{$subscription->id}: " . $e->getMessage());
            }
        }
        
        $this->info("Invoice generation completed.");
    }
}
