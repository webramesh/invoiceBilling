<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class SubscriptionItem extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'name',
        'description',
        'quantity',
        'unit_price',
        'metadata',
        'sort_order',
    ];

    protected $casts = [
        'metadata' => 'array',
        'unit_price' => 'decimal:2',
    ];

    /**
     * Get the subscription that owns this item
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Calculate total price for this item
     */
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->unit_price;
    }
}
