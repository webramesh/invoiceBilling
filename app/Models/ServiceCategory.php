<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class ServiceCategory extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'icon',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
