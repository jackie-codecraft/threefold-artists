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
        'art_form',
        'is_public',
    ];

    protected $casts = [
        'date' => 'date',
        'is_public' => 'boolean',
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
