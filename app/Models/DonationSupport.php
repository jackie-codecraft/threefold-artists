<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DonationSupport extends Model
{
    protected $fillable = [
        'donor_id',
        'stripe_subscription_id',
        'stripe_subscription_schedule_id',
        'stripe_customer_id',
        'stripe_price_id',
        'amount_cents',
        'pending_amount_cents',
        'pending_amount_effective_at',
        'pending_interval',
        'pending_interval_count',
        'currency',
        'interval',
        'interval_count',
        'status',
        'pause_collection_behavior',
        'paused_at',
        'pause_resumes_at',
        'paused_until',
        'cancel_at_period_end',
        'current_period_starts_at',
        'current_period_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'pending_amount_effective_at' => 'datetime',
            'paused_at' => 'datetime',
            'pause_resumes_at' => 'datetime',
            'paused_until' => 'datetime',
            'cancel_at_period_end' => 'boolean',
            'current_period_starts_at' => 'datetime',
            'current_period_ends_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Donor, $this>
     */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * @return HasMany<Donation, $this>
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }
}
