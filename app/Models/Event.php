<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'venue_name',
        'venue_address',
        'latitude',
        'longitude',
        'art_form',
        'is_public',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_public' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now())->orderBy('date');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
