<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Jobs\SendNotificationJob;
use Carbon\Carbon;

class SendPaymentReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminders for upcoming and overdue invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Reminders for invoices due in 3 days
        $upcoming = Invoice::where('status', 'unpaid')
            ->where('due_date', '=', now()->addDays(3)->toDateString())
            ->get();

        foreach ($upcoming as $invoice) {
            SendNotificationJob::dispatch($invoice, ['email', 'whatsapp']);
        }

        // 2. Reminders for overdue invoices (first time overdue)
        $overdue = Invoice::where('status', 'unpaid')
            ->where('due_date', '<', now()->toDateString())
            ->get();

        foreach ($overdue as $invoice) {
            // Here we could add logic to only send once every few days
            SendNotificationJob::dispatch($invoice, ['email', 'whatsapp']);
        }

        $this->info("Dispatched reminders for " . ($upcoming->count() + $overdue->count()) . " invoices.");
    }
}
