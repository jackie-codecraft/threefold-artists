<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\DonationResource\Pages\ListDonations;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Mail\DonationReceipt;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\SiteSettings;
use App\Models\User;
use App\Services\DonationStatementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class OfflineDonationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['name' => 'Office Admin']);
        $this->actingAs($this->admin);
    }

    public function test_admin_records_a_check_for_an_existing_donor_and_receipt_is_emailed(): void
    {
        Mail::fake();
        $donor = Donor::query()->create(['name' => 'Maria Check', 'email' => 'maria@example.com']);

        Livewire::test(ListDonations::class)
            ->mountAction('recordOfflineDonation')
            ->fillForm([
                'donor_id' => $donor->id,
                'amount' => '1,250',
                'received_on' => '2026-10-01',
                'payment_method' => 'check',
                'check_number' => '1042',
                'admin_note' => 'Handed over at gala',
                'send_receipt' => true,
            ])
            ->callMountedAction()
            ->assertHasNoFormErrors();

        $donation = Donation::query()->sole();
        $this->assertSame('manual', $donation->source);
        $this->assertSame('paid', $donation->status);
        $this->assertSame(125_000, $donation->amount_cents);
        $this->assertSame('check', $donation->payment_method);
        $this->assertSame('1042', $donation->check_number);
        $this->assertSame('Handed over at gala', $donation->admin_note);
        $this->assertSame($this->admin->id, $donation->recorded_by_user_id);
        $this->assertSame('2026-10-01', $donation->paid_at->toDateString());
        $this->assertSame('Maria Check', $donation->donor_name);
        $this->assertNotNull($donation->receipt_sent_at);
        $this->assertSame('Check #1042', $donation->paymentMethodLabel());

        Mail::assertSent(DonationReceipt::class, fn (DonationReceipt $mail): bool => $mail->hasTo('maria@example.com')
            && $mail->donation->is($donation));
        $html = (new DonationReceipt($donation))->render();
        $this->assertStringContainsString('Check #1042', $html);
        $this->assertStringContainsString('$1,250.00', $html);
    }

    public function test_cash_gift_from_a_new_donor_without_email_is_recorded_and_no_receipt_sent(): void
    {
        Mail::fake();

        // Same callback the "+ new donor" modal uses; email left blank.
        $newDonorId = ListDonations::createDonor(['name' => '  Cash Giver ', 'email' => '']);

        Livewire::test(ListDonations::class)->mountAction('recordOfflineDonation')->fillForm([
            'donor_id' => $newDonorId,
            'amount' => '40',
            'received_on' => now()->toDateString(),
            'payment_method' => 'cash',
            'send_receipt' => true,
        ])->callMountedAction()->assertHasNoFormErrors();

        $donor = Donor::query()->findOrFail($newDonorId);
        $this->assertNull($donor->email);
        $this->assertSame('Cash Giver', $donor->name);
        $donation = Donation::query()->sole();
        $this->assertSame('cash', $donation->payment_method);
        $this->assertNull($donation->check_number);
        $this->assertNull($donation->receipt_sent_at);
        Mail::assertNothingSent();
    }

    public function test_multiple_donors_without_email_can_coexist(): void
    {
        Donor::query()->create(['name' => 'Anon One']);
        Donor::query()->create(['name' => 'Anon Two', 'email' => '  ']);

        $this->assertSame(2, Donor::query()->whereNull('email')->count());
    }

    public function test_amount_must_be_whole_dollars_and_date_cannot_be_in_future(): void
    {
        $donor = Donor::query()->create(['name' => 'Val', 'email' => 'val@example.com']);

        Livewire::test(ListDonations::class)
            ->mountAction('recordOfflineDonation')
            ->fillForm([
                'donor_id' => $donor->id,
                'amount' => '25.50',
                'received_on' => now()->addDays(3)->toDateString(),
                'payment_method' => 'cash',
            ])
            ->callMountedAction()
            ->assertHasFormErrors(['amount', 'received_on']);

        $this->assertSame(0, Donation::query()->count());
    }

    public function test_offline_gift_flows_into_stats_statement_portal_and_donor_wall(): void
    {
        SiteSettings::current()->update(['donations_enabled' => true]);
        $donor = Donor::query()->create(['name' => 'Wall Friend', 'email' => 'wall@example.com']);

        Livewire::test(ListDonations::class)
            ->mountAction('recordOfflineDonation')
            ->fillForm([
                'donor_id' => $donor->id,
                'amount' => '300',
                'received_on' => now()->toDateString(),
                'payment_method' => 'other',
                'public_recognition_consent' => true,
                'send_receipt' => false,
            ])
            ->callMountedAction()
            ->assertHasNoFormErrors();

        $this->assertTrue($donor->refresh()->public_recognition_consent);

        $stats = $this->stats();
        $this->assertSame('$300', $stats[0]->getValue());
        $this->assertSame('1', $stats[1]->getValue());

        $statement = app(DonationStatementService::class)->statementFor($donor, now()->year);
        $this->assertSame(30_000, $statement['total_cents']);

        $this->get(route('donor-wall'))->assertOk()->assertSee('Wall Friend')->assertSee('$300');
    }

    public function test_anonymous_offline_gift_never_grants_public_recognition(): void
    {
        SiteSettings::current()->update(['donations_enabled' => true]);
        $donor = Donor::query()->create(['name' => 'Quiet Patron', 'email' => 'quiet@example.com']);

        Livewire::test(ListDonations::class)
            ->mountAction('recordOfflineDonation')
            ->fillForm([
                'donor_id' => $donor->id,
                'amount' => '500',
                'received_on' => now()->toDateString(),
                'payment_method' => 'cash',
                'is_anonymous' => true,
                'public_recognition_consent' => true,
                'send_receipt' => false,
            ])
            ->callMountedAction();

        $donation = Donation::query()->sole();
        $this->assertTrue($donation->is_anonymous);
        $this->assertFalse($donation->public_recognition_consent);
        $this->assertFalse((bool) $donor->refresh()->public_recognition_consent);
        $this->get(route('donor-wall'))->assertDontSee('Quiet Patron');
    }

    public function test_void_requires_reason_keeps_the_row_and_removes_it_from_totals(): void
    {
        $donor = Donor::query()->create(['name' => 'Typo Donor', 'email' => 'typo@example.com']);
        $donation = $this->offline($donor, 90_000);

        Livewire::test(ListDonations::class)
            ->mountTableAction('void', $donation->getKey())
            ->set('mountedActions.0.data.reason', '')
            ->callMountedTableAction()
            ->assertHasTableActionErrors(['reason' => 'required']);

        Livewire::test(ListDonations::class)
            ->mountTableAction('void', $donation->getKey())
            ->set('mountedActions.0.data.reason', 'Entered $900 instead of $90')
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors();

        $donation->refresh();
        $this->assertSame('voided', $donation->status);
        $this->assertSame(90_000, $donation->amount_cents);
        $this->assertSame('Entered $900 instead of $90', $donation->void_reason);
        $this->assertSame($this->admin->id, $donation->voided_by_user_id);
        $this->assertNotNull($donation->voided_at);
        $this->assertSame('$0', $this->stats()[0]->getValue());
        $this->assertSame(0, app(DonationStatementService::class)->statementFor($donor, now()->year)['total_cents']);
        $this->assertFalse($donation->isVoidable());
    }

    public function test_donor_portal_shows_offline_gifts_but_hides_voided_entries_and_internal_notes(): void
    {
        SiteSettings::current()->update(['donations_enabled' => true]);
        $donor = Donor::query()->create(['name' => 'Portal Donor', 'email' => 'portal@example.com']);
        $this->offline($donor, 12_300, ['admin_note' => 'SECRET-STAFF-NOTE']);
        $voided = $this->offline($donor, 98_700);
        app(\App\Services\OfflineDonationService::class)->void($voided, 'Duplicate entry', $this->admin);

        $this->withSession(['donor_portal.donor_id' => $donor->id])
            ->get(route('donor-portal'))
            ->assertOk()
            ->assertSee('$123.00')
            ->assertDontSee('$987.00')
            ->assertDontSee('SECRET-STAFF-NOTE');
    }

    public function test_stripe_rows_cannot_be_voided(): void
    {
        $donor = Donor::query()->create(['name' => 'Online', 'email' => 'online@example.com']);
        $stripe = Donation::query()->create([
            'donor_id' => $donor->id,
            'amount' => 50,
            'amount_cents' => 5_000,
            'currency' => 'usd',
            'status' => 'paid',
            'paid_at' => now(),
            'stripe_payment_intent_id' => 'pi_123',
        ]);
        $offline = $this->offline($donor, 2_000);

        $this->assertSame('stripe', $stripe->refresh()->source);
        Livewire::test(ListDonations::class)
            ->assertTableActionHidden('void', $stripe)
            ->assertTableActionVisible('void', $offline)
            ->filterTable('source', 'manual')
            ->assertCanSeeTableRecords([$offline])
            ->assertCanNotSeeTableRecords([$stripe]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        app(\App\Services\OfflineDonationService::class)->void($stripe, 'nope', $this->admin);
    }

    /** @param array<string, mixed> $overrides */
    private function offline(Donor $donor, int $cents, array $overrides = []): Donation
    {
        return Donation::query()->create([
            'donor_id' => $donor->id,
            'donor_name' => $donor->name,
            'donor_email' => $donor->email,
            'amount' => $cents / 100,
            'amount_cents' => $cents,
            'currency' => 'usd',
            'status' => 'paid',
            'source' => 'manual',
            'payment_method' => 'cash',
            'paid_at' => now(),
            ...$overrides,
        ]);
    }

    /** @return array<int, \Filament\Widgets\StatsOverviewWidget\Stat> */
    private function stats(): array
    {
        $method = new \ReflectionMethod(StatsOverviewWidget::class, 'getStats');
        $method->setAccessible(true);

        return $method->invoke(app(StatsOverviewWidget::class));
    }
}
