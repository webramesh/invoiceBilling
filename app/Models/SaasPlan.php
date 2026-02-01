<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'dodo_product_id',
        'price',
        'max_clients',
        'max_invoices_per_month',
        'has_whatsapp',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
