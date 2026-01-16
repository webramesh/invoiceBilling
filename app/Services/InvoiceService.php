<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InvoiceService
{
    /**
     * Generate an invoice for a specific subscription.
     *
     * @param Subscription $subscription
     * @return Invoice
     */
    public function generateForSubscription(Subscription $subscription)
    {
        return DB::transaction(function () use ($subscription) {
            // Generate unique invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'client_id' => $subscription->client_id,
                'subscription_id' => $subscription->id,
                'subtotal' => $subscription->price,
                'tax' => 0,
                'total' => $subscription->price,
                'issue_date' => now(),
                'due_date' => now()->addDays(7),
                'status' => 'unpaid',
            ]);

            // Create Invoice Item
            $invoice->items()->create([
                'description' => $subscription->service->name . ' (' . $subscription->billingCycle->name . ')',
                'quantity' => 1,
                'unit_price' => $subscription->price,
                'total' => $subscription->price,
            ]);

            // Update Subscription Next Billing Date
            $nextDate = Carbon::parse($subscription->next_billing_date)
                ->addMonths($subscription->billingCycle->months);

            $subscription->update([
                'next_billing_date' => $nextDate,
            ]);

            return $invoice;
        });
    }

    /**
     * Process all due subscriptions.
     *
     * @return int Count of generated invoices
     */
    public function processDueSubscriptions()
    {
        $subscriptions = Subscription::with(['client', 'service', 'billingCycle'])
            ->where('status', 'active')
            ->where('next_billing_date', '<=', now()->toDateString())
            ->get();

        $count = 0;
        foreach ($subscriptions as $subscription) {
            $this->generateForSubscription($subscription);
            $count++;
        }

        return $count;
    }
}
