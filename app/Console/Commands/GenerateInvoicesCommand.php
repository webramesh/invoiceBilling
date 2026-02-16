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
    public function handle(\App\Services\InvoiceService $invoiceService)
    {
        $subscriptions = Subscription::where('status', 'active')
            ->where('next_billing_date', '<=', now()->toDateString())
            ->get();

        $this->info("Found " . $subscriptions->count() . " due subscriptions. Dispatching jobs...");

        foreach ($subscriptions as $subscription) {
            // Dispatch the job to the default queue
            // Laravel handles this synchronously if QUEUE_CONNECTION=sync
            // Or asynchronously if QUEUE_CONNECTION=database/redis
            \App\Jobs\ProcessSubscriptionRenewal::dispatch($subscription->id);
            
            $this->info("Queued checks for Subscription #{$subscription->id}");
        }
        
        $this->info("All jobs dispatched.");
    }
}
