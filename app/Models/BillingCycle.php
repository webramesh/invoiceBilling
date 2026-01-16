<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingCycle extends Model
{
    protected $fillable = [
        'name',
        'months',
        'discount_percentage',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
