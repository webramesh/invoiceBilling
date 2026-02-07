<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class NotificationLog extends Model
{
    use BelongsToTenant;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'client_id',
        'type',
        'channel',
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
