<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\DonationSupport;
use App\Models\Donor;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Stripe\StripeClient;

class DonationSupportService
{
    /** @var array<string, list<int>> */
    private const PAUSE_PERIODS = [
        'monthly' => [1, 2, 3],
        'quarterly' => [1, 2],
        'annual' => [1],
    ];

    public function requestPause(Donor $donor, DonationSupport $support, int $periods): void
    {
        if ($support->donor_id !== $donor->id) {
            throw new AuthorizationException;
        }

        if ($support->status !== 'active' || $support->paused_until !== null || $support->cancel_at_period_end || $support->current_period_ends_at === null) {
            throw ValidationException::withMessages(['support' => 'This recurring support is not eligible to be paused.']);
        }

        $allowed = self::PAUSE_PERIODS[$support->interval] ?? [];
        if (! in_array($periods, $allowed, true)) {
            throw ValidationException::withMessages(['periods' => 'That pause length is not available for this support cadence.']);
        }

        $resumesAt = match ($support->interval) {
            'monthly' => $support->current_period_ends_at->copy()->addMonths($periods),
            'quarterly' => $support->current_period_ends_at->copy()->addMonths(3 * $periods),
            'annual' => $support->current_period_ends_at->copy()->addYears($periods),
        };
        $secret = (string) config('services.stripe.secret');
        if (blank($support->stripe_subscription_id) || $secret === '' || $secret === 'sk_test_placeholder') {
            throw ValidationException::withMessages(['support' => 'Online donation management is temporarily unavailable.']);
        }

        (new StripeClient($secret))->subscriptions->update($support->stripe_subscription_id, [
            'pause_collection' => ['behavior' => 'void', 'resumes_at' => $resumesAt->getTimestamp()],
        ]);
        // Stripe webhooks remain authoritative for persisted pause state.
    }

    public function requestAmountChange(Donor $donor, DonationSupport $support, int $amountCents): void
    {
        if ($support->donor_id !== $donor->id) {
            throw new AuthorizationException;
        }

        if ($support->status !== 'active' || $support->cancel_at_period_end || $support->current_period_ends_at === null || $support->pending_amount_cents !== null) {
            throw ValidationException::withMessages(['support' => 'This recurring support is not eligible for an amount change.']);
        }

        if ($amountCents === $support->amount_cents) {
            throw ValidationException::withMessages(['amount' => 'Enter an amount different from your current recurring support.']);
        }

        $secret = (string) config('services.stripe.secret');
        if (blank($support->stripe_subscription_id) || blank($support->stripe_price_id) || $secret === '' || $secret === 'sk_test_placeholder') {
            throw ValidationException::withMessages(['support' => 'Online donation management is temporarily unavailable.']);
        }

        $stripe = new StripeClient($secret);
        $price = $stripe->prices->retrieve($support->stripe_price_id);
        $subscription = $stripe->subscriptions->retrieve($support->stripe_subscription_id);
        $item = $subscription->items->data[0] ?? null;

        if ($item === null || blank($item->id) || blank($price->product)) {
            throw ValidationException::withMessages(['support' => 'This recurring support cannot be updated online.']);
        }

        $newPrice = $stripe->prices->create([
            'currency' => $support->currency,
            'unit_amount' => $amountCents,
            'product' => $price->product,
            'recurring' => match ($support->interval) {
                'monthly' => ['interval' => 'month'],
                'quarterly' => ['interval' => 'month', 'interval_count' => 3],
                'annual' => ['interval' => 'year'],
                default => throw ValidationException::withMessages(['support' => 'This recurring support cannot be updated online.']),
            },
        ]);

        $stripe->subscriptions->update($support->stripe_subscription_id, [
            'items' => [['id' => $item->id, 'price' => $newPrice->id]],
            'proration_behavior' => 'none',
        ]);

        $support->update([
            'pending_amount_cents' => $amountCents,
            'pending_amount_effective_at' => $support->current_period_ends_at,
        ]);
    }

    /** @return list<int> */
    public function allowedPausePeriods(DonationSupport $support): array
    {
        return self::PAUSE_PERIODS[$support->interval] ?? [];
    }
}
