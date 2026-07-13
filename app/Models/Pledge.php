<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Pledge extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'amount',
        'message',
        'public_acknowledgment_consent',
        'status',
        'internal_notes',
        'acknowledged_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'public_acknowledgment_consent' => 'boolean',
        'acknowledged_at' => 'datetime',
    ];

    /**
     * @param  Builder<Pledge>  $query
     * @return Builder<Pledge>
     */
    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }
}
