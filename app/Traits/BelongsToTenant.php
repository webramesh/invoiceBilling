<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function bootBelongsToTenant()
    {
        // Automatically add the user_id (tenant_id) to the model when creating
        static::creating(function ($model) {
            if (! $model->user_id && Auth::check()) {
                $model->user_id = Auth::id();
            }
        });

        // Add a global scope to filter all queries by the authenticated user
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where($builder->getModel()->qualifyColumn('user_id'), Auth::id());
            }
        });
    }

    /**
     * Get the user that owns the model.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
