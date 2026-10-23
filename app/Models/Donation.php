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
        'adjustment_for_donation_id',
        'amount',
        'amount_cents',
        'processing_fee_cents',
        'currency',
        'status',
        'source',
        'payment_method',
        'check_number',
        'admin_note',
        'recorded_by_user_id',
        'is_recurring',
        'recurring_interval',
        'is_anonymous',
        'public_recognition_consent',
        'stripe_payment_id',
        'stripe_checkout_session_id',
        'stripe_payment_intent_id',
        'stripe_invoice_id',
        'stripe_charge_id',
        'stripe_refund_id',
        'stripe_dispute_id',
        'stripe_subscription_id',
        'receipt_sent_at',
        'paid_at',
        'refunded_at',
        'disputed_at',
        'cancelled_at',
        'voided_at',
        'voided_by_user_id',
        'void_reason',
    ];

    public const SOURCE_STRIPE = 'stripe';

    public const SOURCE_MANUAL = 'manual';

    /** @var array<string, string> */
    public const PAYMENT_METHODS = [
        'cash' => 'Cash',
        'check' => 'Check',
        'other' => 'Other',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processing_fee_cents' => 'integer',
        'is_recurring' => 'boolean',
        'is_anonymous' => 'boolean',
        'public_recognition_consent' => 'boolean',
        'receipt_sent_at' => 'datetime',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'disputed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'voided_at' => 'datetime',
    ];

    public function isManual(): bool
    {
        return $this->source === self::SOURCE_MANUAL;
    }

    public function isVoidable(): bool
    {
        return $this->isManual() && $this->status === 'paid' && $this->voided_at === null;
    }

    /** Human label for how the gift was paid, e.g. "Check #1042" or "Card (Stripe)". */
    public function paymentMethodLabel(): string
    {
        if (! $this->isManual()) {
            return 'Card (Stripe)';
        }

        $label = self::PAYMENT_METHODS[$this->payment_method] ?? 'Other';

        return $this->payment_method === 'check' && filled($this->check_number)
            ? "{$label} #{$this->check_number}"
            : $label;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function voidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by_user_id');
    }

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
     * @return BelongsTo<Donation, $this>
     */
    public function adjustmentFor(): BelongsTo
    {
        return $this->belongsTo(self::class, 'adjustment_for_donation_id');
    }

    /**
     * @param  Builder<Donation>  $query
     * @return Builder<Donation>
     */
    public function scopeRecurring(Builder $query): Builder
    {
        return $query->where('is_recurring', true);
    }

    /**
     * @param  Builder<Donation>  $query
     * @return Builder<Donation>
     */
    public function scopeOneTime(Builder $query): Builder
    {
        return $query->where('is_recurring', false);
    }

    /**
     * @param  Builder<Donation>  $query
     * @return Builder<Donation>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('cancelled_at');
    }

    /**
     * Confirmed ledger rows (Stripe and admin-recorded offline gifts), including linked reversals which reduce net paid totals.
     *
     * @param  Builder<Donation>  $query
     * @return Builder<Donation>
     */
    public function scopeConfirmedLedger(Builder $query): Builder
    {
        return $query->whereIn('status', ['paid', 'refunded', 'disputed']);
    }
}
