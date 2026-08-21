<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\DonationSupport;
use App\Models\Donor;
use App\Models\StripeWebhookEvent;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationLedgerTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_donation_fields_remain_usable_with_ledger_defaults(): void
    {
        $donation = Donation::query()->create([
            'donor_name' => 'Legacy Supporter',
            'donor_email' => 'legacy@example.com',
            'amount' => '25.00',
            'is_recurring' => true,
            'recurring_interval' => 'monthly',
            'is_anonymous' => true,
            'stripe_payment_id' => 'pi_legacy',
            'stripe_subscription_id' => 'sub_legacy',
        ]);

        $donation->refresh();

        $this->assertSame('Legacy Supporter', $donation->donor_name);
        $this->assertSame('legacy@example.com', $donation->donor_email);
        $this->assertSame('25.00', $donation->amount);
        $this->assertTrue($donation->is_recurring);
        $this->assertTrue($donation->is_anonymous);
        $this->assertSame('pi_legacy', $donation->stripe_payment_id);
        $this->assertSame('sub_legacy', $donation->stripe_subscription_id);
        $this->assertSame('legacy', $donation->status);
        $this->assertNull($donation->donor_id);
        $this->assertNull($donation->donation_support_id);
    }

    public function test_donations_connect_donors_and_recurring_supports_without_replacing_legacy_fields(): void
    {
        $donor = Donor::query()->create([
            'name' => 'Ledger Supporter',
            'email' => 'SUPPORTER@EXAMPLE.COM',
            'stripe_customer_id' => 'cus_123',
        ]);
        $support = DonationSupport::query()->create([
            'donor_id' => $donor->id,
            'stripe_subscription_id' => 'sub_123',
            'stripe_customer_id' => 'cus_123_subscription',
            'stripe_price_id' => 'price_123',
            'amount_cents' => 1500,
            'currency' => 'usd',
            'interval' => 'month',
            'status' => 'active',
            'pause_collection_behavior' => 'void',
            'paused_until' => now()->addMonth(),
            'cancel_at_period_end' => false,
            'current_period_starts_at' => now()->startOfMonth(),
            'current_period_ends_at' => now()->addMonth()->startOfMonth(),
        ]);
        $donation = Donation::query()->create([
            'donor_name' => $donor->name,
            'donor_email' => $donor->email,
            'donor_id' => $donor->id,
            'donation_support_id' => $support->id,
            'amount' => '15.00',
            'amount_cents' => 1500,
            'currency' => 'usd',
            'status' => 'paid',
            'is_recurring' => true,
            'recurring_interval' => 'monthly',
            'stripe_payment_id' => 'pi_123',
            'stripe_payment_intent_id' => 'pi_123_ledger',
            'stripe_invoice_id' => 'in_123',
            'paid_at' => now(),
        ]);

        $this->assertSame('supporter@example.com', $donor->email);
        $this->assertTrue($donation->donor->is($donor));
        $this->assertTrue($donation->donationSupport->is($support));
        $this->assertTrue($donor->donations->contains($donation));
        $this->assertTrue($support->donations->contains($donation));
        $this->assertSame('paid', $donation->status);
        $this->assertSame(1500, $donation->amount_cents);
        $this->assertSame('15.00', $donation->amount);
    }

    public function test_stripe_webhook_events_keep_payload_audit_data_and_deduplicate_events(): void
    {
        $event = StripeWebhookEvent::query()->create([
            'stripe_event_id' => 'evt_123',
            'event_type' => 'invoice.paid',
            'api_version' => '2025-01-27.acacia',
            'livemode' => false,
            'payload' => ['id' => 'evt_123', 'type' => 'invoice.paid'],
            'received_at' => now(),
        ]);

        $this->assertSame('invoice.paid', $event->event_type);
        $this->assertSame('evt_123', $event->payload['id']);
        $this->assertFalse($event->livemode);

        $this->expectException(QueryException::class);

        StripeWebhookEvent::query()->create([
            'stripe_event_id' => 'evt_123',
            'event_type' => 'invoice.paid',
            'payload' => [],
        ]);
    }
}
