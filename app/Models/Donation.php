<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'donor_name',
        'donor_email',
        'amount',
        'is_recurring',
        'is_anonymous',
        'stripe_payment_id',
        'receipt_sent_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
        'is_anonymous' => 'boolean',
        'receipt_sent_at' => 'datetime',
    ];
}
