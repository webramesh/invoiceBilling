<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToTenant;

class Client extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'company',
        'whatsapp_number',
        'status',
        'portal_hash',
        'address',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($client) {
            $client->portal_hash = \Illuminate\Support\Str::random(32);
        });
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function notifications()
    {
        return $this->hasMany(NotificationLog::class);
    }
}
