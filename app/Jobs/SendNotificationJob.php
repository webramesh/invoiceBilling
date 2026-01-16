<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invoice;
    protected $channels;

    /**
     * Create a new job instance.
     */
    public function __construct(Invoice $invoice, array $channels = ['email'])
    {
        $this->invoice = $invoice;
        $this->channels = $channels;
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        $notificationService->sendInvoice($this->invoice, $this->channels);
    }
}
