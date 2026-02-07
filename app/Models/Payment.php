<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Payment extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'user_id',
        'invoice_id',
        'payment_date',
        'amount',
        'payment_method',
        'transaction_reference',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
