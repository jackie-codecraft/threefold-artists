<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Support\Collection;

class DonationStatementService
{
    /**
     * @return array{year: int, entries: Collection<int, Donation>, total_cents: int}
     */
    public function statementFor(Donor $donor, int $year): array
    {
        $entries = $donor->donations()
            ->whereIn('status', ['paid', 'refunded', 'disputed'])
            ->whereNotNull('amount_cents')
            ->orderBy('id')
            ->get()
            ->filter(function (Donation $donation) use ($year): bool {
                return $this->effectiveDate($donation)?->year === $year;
            })
            ->sortBy(fn (Donation $donation): string => $this->effectiveDate($donation)?->toDateTimeString() ?? '')
            ->values();

        return [
            'year' => $year,
            'entries' => $entries,
            'total_cents' => $entries->sum(fn (Donation $donation): int => (int) $donation->amount_cents),
        ];
    }

    public function effectiveDate(Donation $donation): mixed
    {
        return match ($donation->status) {
            'paid' => $donation->paid_at,
            'refunded' => $donation->refunded_at,
            'disputed' => $donation->disputed_at,
            default => null,
        };
    }
}
