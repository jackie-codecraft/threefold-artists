<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'donor_name',
        'donor_email',
        'amount',
        'is_recurring',
        'recurring_interval',
        'is_anonymous',
        'stripe_payment_id',
        'stripe_subscription_id',
        'receipt_sent_at',
        'cancelled_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
        'is_anonymous' => 'boolean',
        'receipt_sent_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * @param Builder<Donation> $query
     * @return Builder<Donation>
     */
    public function scopeRecurring(Builder $query): Builder
    {
        return $query->where('is_recurring', true);
    }

    /**
     * @param Builder<Donation> $query
     * @return Builder<Donation>
     */
    public function scopeOneTime(Builder $query): Builder
    {
        return $query->where('is_recurring', false);
    }

    /**
     * @param Builder<Donation> $query
     * @return Builder<Donation>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('cancelled_at');
    }
}
