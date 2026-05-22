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
        'status',
        'internal_notes',
        'acknowledged_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'acknowledged_at' => 'datetime',
    ];

    /**
     * @param Builder<Pledge> $query
     * @return Builder<Pledge>
     */
    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }
}
