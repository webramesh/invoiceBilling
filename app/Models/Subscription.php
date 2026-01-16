<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'client_id',
        'service_id',
        'billing_cycle_id',
        'start_date',
        'next_billing_date',
        'price',
        'status',
        'auto_renewal',
    ];

    protected $casts = [
        'start_date' => 'date',
        'next_billing_date' => 'date',
        'auto_renewal' => 'boolean',
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
}
