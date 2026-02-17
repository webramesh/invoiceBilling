<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Subscription extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'user_id',
        'client_id',
        'service_id',
        'service_alias',
        'billing_cycle_id',
        'start_date',
        'next_billing_date',
        'price',
        'quantity',
        'status',
        'auto_renewal',
        'whatsapp_notifications',
        'email_notifications',
        'reminders_enabled',
        'last_reminders_sent',
        'custom_reminder_days',
    ];

    protected $casts = [
        'start_date' => 'date',
        'next_billing_date' => 'date',
        'auto_renewal' => 'boolean',
        'whatsapp_notifications' => 'boolean',
        'email_notifications' => 'boolean',
        'reminders_enabled' => 'boolean',
        'last_reminders_sent' => 'array',
        'custom_reminder_days' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function billingCycle()
    {
        return $this->belongsTo(BillingCycle::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function items()
    {
        return $this->hasMany(SubscriptionItem::class)->orderBy('sort_order');
    }

    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class);
    }

    /**
     * Get total price including all items
     */
    public function getTotalWithItemsAttribute()
    {
        $baseTotal = $this->price * ($this->quantity ?? 1);
        $itemsTotal = $this->items->sum('total_price');
        return $baseTotal + $itemsTotal;
    }
}
