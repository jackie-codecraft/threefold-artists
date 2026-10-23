<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Grosses up a gift so that, after Stripe's percentage + fixed fee, the
 * organization nets at least the intended gift.
 */
final class ProcessingFee
{
    public static function percent(): float
    {
        return (float) config('donations.processing_fee.percent', 2.9);
    }

    public static function fixedCents(): int
    {
        return (int) config('donations.processing_fee.fixed_cents', 30);
    }

    /** Fee (in cents) the donor adds so the gift arrives whole. */
    public static function forGift(int $giftCents): int
    {
        if ($giftCents <= 0) {
            return 0;
        }

        $rate = self::percent() / 100;
        $total = (int) ceil(($giftCents + self::fixedCents()) / (1 - $rate));

        return $total - $giftCents;
    }
}
