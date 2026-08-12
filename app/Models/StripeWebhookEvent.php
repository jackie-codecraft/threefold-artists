<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeWebhookEvent extends Model
{
    protected $fillable = [
        'stripe_event_id',
        'event_type',
        'api_version',
        'livemode',
        'payload',
        'received_at',
        'processed_at',
        'processing_error',
    ];

    protected function casts(): array
    {
        return [
            'livemode' => 'boolean',
            'payload' => 'array',
            'received_at' => 'datetime',
            'processed_at' => 'datetime',
        ];
    }
}
