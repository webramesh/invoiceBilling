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
            
            $quantity = $subscription->quantity ?? 1;
            $total = $subscription->price * $quantity;

            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'client_id' => $subscription->client_id,
                'subscription_id' => $subscription->id,
                'subtotal' => $total,
                'tax' => 0,
                'total' => $total,
                'issue_date' => now(),
                'due_date' => now()->addDays(7),
                'status' => 'unpaid',
            ]);

            // Description with Alias if exists
            $description = $subscription->service->name;
            if ($subscription->service_alias) {
                $description .= ' - ' . $subscription->service_alias;
            }
            $description .= ' (' . $subscription->billingCycle->name . ')';

            // Create Invoice Item
            $invoice->items()->create([
                'description' => $description,
                'quantity' => $quantity,
                'unit_price' => $subscription->price,
                'total' => $total,
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
