<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\Donor;
use App\Services\DonationStatementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DonationStatementTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function statement_uses_only_local_ledger_cents_and_effective_dates_with_calendar_year_boundaries(): void
    {
        $donor = Donor::query()->create(['email' => 'supporter@example.com']);
        $this->entry($donor, 'paid', 1500, '2025-12-31 23:59:59');
        $this->entry($donor, 'paid', 2500, '2026-01-01 00:00:00');
        $this->entry($donor, 'refunded', -500, '2026-02-01 12:00:00');
        $this->entry($donor, 'disputed', -1000, '2026-12-31 23:59:59');
        Donation::query()->create(['donor_id' => $donor->id, 'amount' => '99.00', 'status' => 'legacy']);

        $statement = app(DonationStatementService::class)->statementFor($donor, 2026);

        $this->assertSame(1000, $statement['total_cents']);
        $this->assertSame(['paid', 'refunded', 'disputed'], $statement['entries']->pluck('status')->all());
    }

    #[Test]
    public function statement_route_requires_the_donor_portal_session_and_renders_approved_tax_text(): void
    {
        Carbon::setTestNow('2026-06-01');
        $donor = Donor::query()->create(['email' => 'supporter@example.com']);
        $this->entry($donor, 'paid', 2500, '2026-01-02 00:00:00');

        $this->get(route('donor-portal.statement', ['year' => 2026]))->assertRedirect(route('donor-access.request'));
        $this->withSession(['donor_portal.donor_id' => $donor->id])
            ->get(route('donor-portal.statement', ['year' => 2026]))
            ->assertOk()
            ->assertSee('Threefold Artists, Inc.')
            ->assertSee('This annual giving summary is a record of payments and adjustments.')
            ->assertSee('85-0567934')
            ->assertSee('tax-deductible', false)
            ->assertSee('501(c)(3)', false)
            ->assertSee('@media print', false)
            ->assertSee('header,', false)
            ->assertSee('footer,', false)
            ->assertSee('display: none !important;', false);
    }

    private function entry(Donor $donor, string $status, int $cents, string $date): void
    {
        Donation::query()->create([
            'donor_id' => $donor->id, 'amount' => $cents / 100, 'amount_cents' => $cents, 'status' => $status,
            match ($status) {
                'paid' => 'paid_at', 'refunded' => 'refunded_at', 'disputed' => 'disputed_at'
            } => Carbon::parse($date, 'UTC'),
        ]);
    }
}
