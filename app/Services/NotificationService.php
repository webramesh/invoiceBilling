<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send an invoice notification.
     *
     * @param Invoice $invoice
     * @param array $channels
     * @return void
     */
    public function sendInvoice(Invoice $invoice, $channels = ['email'])
    {
        foreach ($channels as $channel) {
            try {
                if ($channel === 'email') {
                    $this->sendEmail($invoice);
                } elseif ($channel === 'whatsapp') {
                    $this->sendWhatsApp($invoice);
                }
            } catch (\Exception $e) {
                Log::error("Failed to send notification via {$channel}: " . $e->getMessage());

                NotificationLog::create([
                    'client_id' => $invoice->client_id,
                    'type' => 'invoice',
                    'channel' => $channel,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Send invoice via Email.
     */
    protected function sendEmail(Invoice $invoice)
    {
        Mail::to($invoice->client->email)->send(new InvoiceMail($invoice));

        NotificationLog::create([
            'client_id' => $invoice->client_id,
            'type' => 'invoice',
            'channel' => 'email',
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Send invoice via WhatsApp.
     */
    protected function sendWhatsApp(Invoice $invoice)
    {
        $whatsappUrl = env('WHATSAPP_SERVICE_URL', 'http://localhost:3001/send-message');
        $phone = $invoice->client->phone;

        // Basic message template
        $dueDate = \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y');
        $message = "Hello {$invoice->client->name}, your invoice #{$invoice->invoice_number} for amount " . number_format($invoice->total, 2) . " is ready. Please pay by " . $dueDate . ".";

        $response = Http::post($whatsappUrl, [
            'phone' => $phone,
            'message' => $message,
            'api_key' => env('WHATSAPP_API_KEY'),
        ]);

        if ($response->successful()) {
            NotificationLog::create([
                'client_id' => $invoice->client_id,
                'type' => 'invoice',
                'channel' => 'whatsapp',
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } else {
            throw new \Exception("WhatsApp service returned error: " . $response->body());
        }
    }
}
