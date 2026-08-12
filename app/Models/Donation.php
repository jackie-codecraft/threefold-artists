<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $fillable = [
        'donor_name',
        'donor_email',
        'donor_id',
        'donation_support_id',
        'amount',
        'amount_cents',
        'currency',
        'status',
        'is_recurring',
        'recurring_interval',
        'is_anonymous',
        'stripe_payment_id',
        'stripe_checkout_session_id',
        'stripe_payment_intent_id',
        'stripe_invoice_id',
        'stripe_charge_id',
        'stripe_subscription_id',
        'receipt_sent_at',
        'paid_at',
        'refunded_at',
        'disputed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
        'is_anonymous' => 'boolean',
        'receipt_sent_at' => 'datetime',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'disputed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Donor, $this>
     */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * @return BelongsTo<DonationSupport, $this>
     */
    public function donationSupport(): BelongsTo
    {
        return $this->belongsTo(DonationSupport::class);
    }

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
