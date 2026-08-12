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
            throw new AuthorizationException();
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

    /** @return list<int> */
    public function allowedPausePeriods(DonationSupport $support): array
    {
        return self::PAUSE_PERIODS[$support->interval] ?? [];
    }
}
