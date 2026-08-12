<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\DonationResource\Pages\ListDonations;
use App\Filament\Resources\PledgeResource\Pages\EditPledge;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Models\Donation;
use App\Models\DonationSupport;
use App\Models\Donor;
use App\Models\Pledge;
use App\Models\SiteSettings;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DonationAdminAccuracyTest extends TestCase
{
    use RefreshDatabase;

    public function test_donor_wall_only_includes_explicitly_consented_confirmed_paid_ledger_rows_and_nets_reversals(): void
    {
        SiteSettings::current()->update(['donations_enabled' => true]);
        $recognized = Donor::query()->create(['name' => 'Recognized Donor', 'email' => 'recognized@example.com', 'public_recognition_consent' => true]);
        $noConsent = Donor::query()->create(['name' => 'No Consent Donor', 'email' => 'no-consent@example.com']);
        $anonymous = Donor::query()->create(['name' => 'Anonymous Donor', 'email' => 'anonymous@example.com']);

        $paid = $this->donation($recognized, 10_000, ['public_recognition_consent' => true]);
        $this->donation($recognized, -2_500, [
            'status' => 'refunded',
            'public_recognition_consent' => true,
            'adjustment_for_donation_id' => $paid->id,
        ]);
        $this->donation($noConsent, 50_000); // is_anonymous=false is not public recognition consent.
        $this->donation($anonymous, 50_000, ['is_anonymous' => true, 'public_recognition_consent' => true]);
        $this->donation($recognized, 50_000, ['status' => 'legacy', 'public_recognition_consent' => true]);

        $this->get(route('donor-wall'))
            ->assertOk()
            ->assertSee('Recognized Donor')
            ->assertSee('$75')
            ->assertSee('1')
            ->assertDontSee('No Consent Donor')
            ->assertDontSee('Anonymous Donor');
    }

    public function test_admin_stats_use_net_confirmed_ledger_paid_dates_distinct_paid_donors_and_active_supports(): void
    {
        $paidDonor = Donor::query()->create(['name' => 'Paid', 'email' => 'paid@example.com']);
        $otherDonor = Donor::query()->create(['name' => 'Other', 'email' => 'other@example.com']);
        $paid = $this->donation($paidDonor, 10_000);
        $this->donation($paidDonor, -2_500, ['status' => 'refunded', 'adjustment_for_donation_id' => $paid->id]);
        $this->donation($otherDonor, 5_000, ['status' => 'legacy']);
        $this->donation($otherDonor, 9_999, ['status' => 'paid', 'paid_at' => now()->subMonth()]);
        DonationSupport::query()->create([
            'donor_id' => $paidDonor->id,
            'amount_cents' => 1000,
            'currency' => 'usd',
            'status' => 'active',
        ]);
        DonationSupport::query()->create([
            'donor_id' => $otherDonor->id,
            'amount_cents' => 1000,
            'currency' => 'usd',
            'status' => 'canceled',
        ]);

        $stats = $this->stats();

        $this->assertSame('$175', $stats[0]->getValue());
        $this->assertSame('$75 net paid this month', $stats[0]->getDescription());
        $this->assertSame('2', $stats[1]->getValue());
        $this->assertSame('1 active recurring supports', $stats[1]->getDescription());
    }

    public function test_donation_ledger_is_filterable_but_has_no_create_edit_or_delete_actions(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get('/admin/donations/create')->assertNotFound();
        Livewire::test(ListDonations::class)
            ->assertTableHeaderActionsExistInOrder([])
            ->assertTableActionsExistInOrder([])
            ->assertTableBulkActionsExistInOrder([])
            ->assertTableFilterExists('status')
            ->assertTableFilterExists('receipt_sent')
            ->assertTableFilterExists('adjustments');
    }

    public function test_historical_founding_supporter_pledges_keep_source_facts_read_only_and_have_no_delete_controls(): void
    {
        $user = User::factory()->create();
        $pledge = Pledge::query()->create([
            'name' => 'Founding Supporter',
            'email' => 'founding@example.com',
            'amount' => 250,
            'public_acknowledgment_consent' => true,
            'status' => 'new',
        ]);
        $this->actingAs($user);

        $this->get('/admin/pledges')
            ->assertOk()
            ->assertSee('Historical Founding Supporter Pledges');
        $this->get("/admin/pledges/{$pledge->id}/edit")
            ->assertOk()
            ->assertSee('Historical source record')
            ->assertDontSee('Delete');
        Livewire::test(EditPledge::class, ['record' => $pledge->getRouteKey()])
            ->assertFormFieldIsDisabled('name')
            ->assertFormFieldIsDisabled('email')
            ->assertFormFieldIsDisabled('amount')
            ->assertFormFieldIsDisabled('public_acknowledgment_consent');
    }

    /** @param array<string, mixed> $overrides */
    private function donation(Donor $donor, int $amountCents, array $overrides = []): Donation
    {
        return Donation::query()->create([
            'donor_id' => $donor->id,
            'donor_name' => $donor->name,
            'donor_email' => $donor->email,
            'amount' => $amountCents / 100,
            'amount_cents' => $amountCents,
            'currency' => 'usd',
            'status' => 'paid',
            'is_anonymous' => false,
            'paid_at' => now(),
            ...$overrides,
        ]);
    }

    /** @return array<int, Stat> */
    private function stats(): array
    {
        $method = new \ReflectionMethod(StatsOverviewWidget::class, 'getStats');
        $method->setAccessible(true);

        return $method->invoke(app(StatsOverviewWidget::class));
    }
}
