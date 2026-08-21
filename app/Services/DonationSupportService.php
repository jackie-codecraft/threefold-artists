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

    public function requestAmountChange(Donor $donor, DonationSupport $support, int $amountCents, string $cadence): void
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

        $product = $stripe->products->retrieve((string) $price->product);
        if (! $product->active) {
            $product = $stripe->products->create([
                'name' => ucfirst($support->interval).' Donation to Threefold Artists',
            ]);
        }

        $newPrice = $stripe->prices->create([
            'currency' => $support->currency,
            'unit_amount' => $amountCents,
            'product' => $product->id,
            'recurring' => match ($cadence) {
                'monthly' => ['interval' => 'month'],
                'quarterly' => ['interval' => 'month', 'interval_count' => 3],
                'annual' => ['interval' => 'year'],
                default => throw ValidationException::withMessages(['support' => 'This recurring support cannot be updated online.']),
            },
        ]);

        if ($cadence === $support->interval) {
            $stripe->subscriptions->update($support->stripe_subscription_id, [
                'items' => [['id' => $item->id, 'price' => $newPrice->id]],
                'proration_behavior' => 'none',
            ]);
        } else {
            $currentPriceId = (string) $support->stripe_price_id;
            $currentPeriodStart = $item->current_period_start ?? $support->current_period_starts_at?->getTimestamp();
            $currentPeriodEnd = $item->current_period_end ?? $support->current_period_ends_at?->getTimestamp();
            if (! is_int($currentPeriodStart) || ! is_int($currentPeriodEnd)) {
                throw ValidationException::withMessages(['support' => 'This recurring support is missing its renewal schedule.']);
            }

            $schedule = $stripe->subscriptionSchedules->create(['from_subscription' => $support->stripe_subscription_id]);
            $stripe->subscriptionSchedules->update($schedule->id, [
                'end_behavior' => 'release',
                'proration_behavior' => 'none',
                'phases' => [
                    [
                        'start_date' => $currentPeriodStart,
                        'end_date' => $currentPeriodEnd,
                        'items' => [['price' => $currentPriceId, 'quantity' => 1]],
                        'proration_behavior' => 'none',
                    ],
                    [
                        'items' => [['price' => $newPrice->id, 'quantity' => 1]],
                        'iterations' => 1,
                        'proration_behavior' => 'none',
                    ],
                ],
            ]);
            $support->stripe_subscription_schedule_id = $schedule->id;
        }

        $support->update([
            'pending_amount_cents' => $amountCents,
            'pending_amount_effective_at' => $support->current_period_ends_at,
            'pending_interval' => $cadence,
            'pending_interval_count' => $cadence === 'quarterly' ? 3 : 1,
            'stripe_subscription_schedule_id' => $support->stripe_subscription_schedule_id,
        ]);
    }

    /** @return list<int> */
    public function allowedPausePeriods(DonationSupport $support): array
    {
        return self::PAUSE_PERIODS[$support->interval] ?? [];
    }
}
