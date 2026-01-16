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
        $count = $invoiceService->processDueSubscriptions();
        $this->info("Generated {$count} invoices.");
    }
}
