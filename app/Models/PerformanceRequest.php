<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceRequest extends Model
{
    protected $fillable = [
        'organization_name',
        'venue_type',
        'contact_name',
        'contact_email',
        'contact_phone',
        'address',
        'audience_size',
        'audience_demographics',
        'preferred_art_form',
        'preferred_dates',
        'accessibility_requirements',
        'notes',
        'status',
        'reply',
        'replied_at',
        'internal_notes',
    ];

    protected $casts = [
        'audience_size' => 'integer',
        'replied_at' => 'datetime',
    ];

    public function isReplied(): bool
    {
        return $this->status === 'replied';
    }
}
