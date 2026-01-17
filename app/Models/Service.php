<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'service_category_id',
        'name',
        'description',
        'base_price',
        'status',
        'tax_status',
        'tax_rate',
        'billing_options',
        'is_draft',
    ];

    protected $casts = [
        'billing_options' => 'array',
        'is_draft' => 'boolean',
        'base_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
