<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Subscription;
use App\Models\Setting;

class ReminderMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;
    public $reminderType;
    public $daysUntilDue;
    public $subject;
    public $body;

    /**
     * Create a new message instance.
     */
    public function __construct(Subscription $subscription, string $reminderType, int $daysUntilDue)
    {
        $this->subscription = $subscription;
        $this->reminderType = $reminderType;
        $this->daysUntilDue = $daysUntilDue;
        
        // Get email template from settings for this specific tenant
        $settings = Setting::where('user_id', $subscription->user_id)->pluck('value', 'key');
        
        $subjectTemplate = $settings['reminder_email_subject'] ?? 'Upcoming Invoice Reminder - {service_name}';
        $bodyTemplate = $settings['reminder_email_body'] ?? $this->getDefaultBody();
        
        // Replace placeholders
        $this->subject = $this->replacePlaceholders($subjectTemplate);
        $this->body = $this->replacePlaceholders($bodyTemplate);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reminder',
            with: [
                'subscription' => $this->subscription,
                'daysUntilDue' => $this->daysUntilDue,
                'body' => $this->body,
            ],
        );
    }

    /**
     * Replace placeholders in template
     */
    private function replacePlaceholders(string $template): string
    {
        $amount = number_format($this->subscription->price * ($this->subscription->quantity ?? 1), 2);
        
        $replacements = [
            '{client_name}' => $this->subscription->client->name ?? 'Valued Customer',
            '{service_name}' => $this->subscription->service_alias ?? $this->subscription->service->name ?? 'Service',
            '{amount}' => $amount,
            '{currency}' => '$', // TODO: Make this configurable
            '{due_date}' => $this->subscription->next_billing_date->format('F j, Y'),
            '{days_until_due}' => $this->daysUntilDue,
            '{billing_cycle}' => $this->subscription->billingCycle->name ?? 'Monthly',
        ];
        
        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    /**
     * Get default email body template
     */
    private function getDefaultBody(): string
    {
        return 'Dear {client_name},

This is a friendly reminder that your invoice for {service_name} will be due in {days_until_due} days.

Invoice Amount: {currency}{amount}
Due Date: {due_date}
Billing Cycle: {billing_cycle}

Please ensure timely payment to avoid any service interruption.

Thank you for your business!';
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
