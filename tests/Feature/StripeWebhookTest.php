<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\DonationReceipt;
use App\Models\Donation;
use App\Models\DonationSupport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.stripe.webhook_secret' => 'whsec_test_webhook']);
        Mail::fake();
    }

    public function test_signed_checkout_event_creates_one_paid_donation_and_receipt_after_commit(): void
    {
        $payload = $this->event('evt_checkout_paid', 'checkout.session.completed', [
            'id' => 'cs_paid_123',
            'mode' => 'payment',
            'payment_status' => 'paid',
            'amount_total' => 2500,
            'currency' => 'usd',
            'payment_intent' => 'pi_paid_123',
            'customer' => 'cus_paid_123',
            'customer_email' => 'supporter@example.com',
            'customer_details' => ['name' => 'Supporter'],
            'metadata' => ['donor_name' => 'Supporter', 'is_anonymous' => '0', 'public_recognition_consent' => '1'],
        ]);

        $this->postJson('/stripe/webhook', $payload, $this->signature($payload))
            ->assertOk()->assertJson(['received' => true]);
        $this->postJson('/stripe/webhook', $payload, $this->signature($payload))->assertOk();

        $this->assertDatabaseCount('stripe_webhook_events', 1);
        $this->assertDatabaseCount('donations', 1);
        $this->assertDatabaseHas('donations', [
            'stripe_checkout_session_id' => 'cs_paid_123',
            'amount_cents' => 2500,
            'status' => 'paid',
            'public_recognition_consent' => 1,
        ]);
        Mail::assertSent(DonationReceipt::class, 1);
    }

    public function test_recurring_invoices_are_ledgered_once_and_subscription_events_update_support_state(): void
    {
        $subscription = $this->event('evt_subscription_updated', 'customer.subscription.updated', [
            'id' => 'sub_123',
            'customer' => 'cus_123',
            'customer_email' => 'monthly@example.com',
            'customer_name' => 'Monthly Donor',
            'status' => 'active',
            'cancel_at_period_end' => true,
            'current_period_start' => 1_700_000_000,
            'current_period_end' => 1_700_100_000,
            'pause_collection' => ['behavior' => 'void', 'resumes_at' => 1_700_200_000],
            'items' => ['data' => [['price' => ['id' => 'price_123', 'unit_amount' => 1500, 'currency' => 'usd', 'recurring' => ['interval' => 'month', 'interval_count' => 1]]]]],
        ]);
        $this->postJson('/stripe/webhook', $subscription, $this->signature($subscription))->assertOk();

        $invoice = $this->event('evt_invoice_paid', 'invoice.paid', [
            'id' => 'in_123',
            'subscription' => 'sub_123',
            'customer' => 'cus_123',
            'customer_email' => 'monthly@example.com',
            'customer_name' => 'Monthly Donor',
            'status' => 'paid',
            'amount_paid' => 1500,
            'currency' => 'usd',
            'payment_intent' => 'pi_invoice_123',
            'charge' => 'ch_invoice_123',
            'period_start' => 1_700_000_000,
            'period_end' => 1_700_100_000,
            'lines' => ['data' => [['price' => ['id' => 'price_123', 'unit_amount' => 1500, 'currency' => 'usd', 'recurring' => ['interval' => 'month', 'interval_count' => 1]]]]],
        ]);
        $this->postJson('/stripe/webhook', $invoice, $this->signature($invoice))->assertOk();
        $this->postJson('/stripe/webhook', $invoice, $this->signature($invoice))->assertOk();

        $this->assertDatabaseCount('donations', 1);
        $this->assertDatabaseHas('donation_supports', [
            'stripe_subscription_id' => 'sub_123',
            'status' => 'active',
            'cancel_at_period_end' => 1,
            'pause_collection_behavior' => 'void',
            'interval' => 'monthly',
            'interval_count' => 1,
        ]);
    }

    public function test_stripe_recurring_cadences_are_canonicalized_and_persist_the_price_interval_count(): void
    {
        $quarterly = $this->event('evt_subscription_quarterly', 'customer.subscription.created', [
            'id' => 'sub_quarterly', 'customer' => 'cus_quarterly', 'customer_email' => 'quarterly@example.com',
            'status' => 'active', 'items' => ['data' => [['price' => [
                'id' => 'price_quarterly', 'unit_amount' => 3000, 'currency' => 'usd',
                'recurring' => ['interval' => 'month', 'interval_count' => 3],
            ]]]],
        ]);

        $this->postJson('/stripe/webhook', $quarterly, $this->signature($quarterly))->assertOk();

        $this->assertDatabaseHas('donation_supports', [
            'stripe_subscription_id' => 'sub_quarterly', 'interval' => 'quarterly', 'interval_count' => 3,
        ]);
    }

    public function test_refund_creates_linked_negative_adjustment_without_mutating_paid_donation(): void
    {
        $checkout = $this->event('evt_checkout_refundable', 'checkout.session.completed', [
            'id' => 'cs_refundable', 'mode' => 'payment', 'payment_status' => 'paid',
            'amount_total' => 2500, 'currency' => 'usd', 'payment_intent' => 'pi_refundable',
            'customer_email' => 'refund@example.com', 'customer_details' => ['name' => 'Refund Donor'], 'metadata' => [],
        ]);
        $this->postJson('/stripe/webhook', $checkout, $this->signature($checkout))->assertOk();
        Donation::query()->where('stripe_checkout_session_id', 'cs_refundable')->update(['stripe_charge_id' => 'ch_refundable']);

        $refund = $this->event('evt_charge_refunded', 'charge.refunded', [
            'id' => 'ch_refundable', 'payment_intent' => 'pi_refundable',
            'refunds' => ['data' => [['id' => 're_refundable', 'status' => 'succeeded', 'amount' => 2500, 'currency' => 'usd', 'created' => 1_700_000_000]]],
        ]);
        $this->postJson('/stripe/webhook', $refund, $this->signature($refund))->assertOk();

        $paid = Donation::query()->where('stripe_checkout_session_id', 'cs_refundable')->firstOrFail();
        $adjustment = Donation::query()->where('stripe_refund_id', 're_refundable')->firstOrFail();
        $this->assertSame('paid', $paid->status);
        $this->assertSame(2500, $paid->amount_cents);
        $this->assertSame($paid->id, $adjustment->adjustment_for_donation_id);
        $this->assertSame(-2500, $adjustment->amount_cents);
    }

    public function test_failed_invoice_and_deleted_subscription_update_recurring_support_without_creating_money_rows(): void
    {
        $created = $this->event('evt_subscription_created', 'customer.subscription.created', [
            'id' => 'sub_lifecycle', 'customer' => 'cus_lifecycle', 'customer_email' => 'lifecycle@example.com',
            'status' => 'active', 'cancel_at_period_end' => false,
            'items' => ['data' => [['price' => ['id' => 'price_lifecycle', 'unit_amount' => 2000, 'currency' => 'usd', 'recurring' => ['interval' => 'month', 'interval_count' => 1]]]]],
        ]);
        $this->postJson('/stripe/webhook', $created, $this->signature($created))->assertOk();

        $failed = $this->event('evt_invoice_failed', 'invoice.payment_failed', [
            'id' => 'in_failed', 'subscription' => 'sub_lifecycle', 'status' => 'open', 'amount_due' => 2000,
        ]);
        $this->postJson('/stripe/webhook', $failed, $this->signature($failed))->assertOk();
        $this->assertDatabaseHas('donation_supports', ['stripe_subscription_id' => 'sub_lifecycle', 'status' => 'past_due']);

        $deleted = $this->event('evt_subscription_deleted', 'customer.subscription.deleted', [
            'id' => 'sub_lifecycle', 'customer' => 'cus_lifecycle', 'customer_email' => 'lifecycle@example.com',
            'status' => 'canceled', 'cancel_at_period_end' => false,
            'items' => ['data' => [['price' => ['id' => 'price_lifecycle', 'unit_amount' => 2000, 'currency' => 'usd', 'recurring' => ['interval' => 'month', 'interval_count' => 1]]]]],
        ]);
        $this->postJson('/stripe/webhook', $deleted, $this->signature($deleted))->assertOk();

        $this->assertDatabaseHas('donation_supports', ['stripe_subscription_id' => 'sub_lifecycle', 'status' => 'canceled']);
        $this->assertDatabaseCount('donations', 0);
    }

    public function test_modern_invoice_payload_creates_a_donor_support_and_recurring_ledger_entry(): void
    {
        $invoice = $this->event('evt_modern_invoice_paid', 'invoice.paid', [
            'id' => 'in_modern_123',
            'status' => 'paid',
            'customer' => 'cus_modern_123',
            'customer_email' => 'modern@example.com',
            'customer_name' => 'Modern Donor',
            'amount_paid' => 2500,
            'currency' => 'usd',
            'payment_intent' => 'pi_modern_123',
            'parent' => ['subscription_details' => ['subscription' => 'sub_modern_123', 'metadata' => []]],
            'lines' => ['data' => [[
                'currency' => 'usd',
                'pricing' => ['price_details' => ['price' => 'price_modern_123'], 'unit_amount_decimal' => '2500'],
                'parent' => ['subscription_item_details' => ['subscription' => 'sub_modern_123']],
            ]]],
        ]);

        $this->postJson('/stripe/webhook', $invoice, $this->signature($invoice))->assertOk();

        $this->assertDatabaseHas('donors', ['email' => 'modern@example.com', 'stripe_customer_id' => 'cus_modern_123']);
        $this->assertDatabaseHas('donation_supports', ['stripe_subscription_id' => 'sub_modern_123', 'stripe_customer_id' => 'cus_modern_123']);
        $this->assertDatabaseHas('donations', ['stripe_invoice_id' => 'in_modern_123', 'stripe_subscription_id' => 'sub_modern_123', 'amount_cents' => 2500]);
    }

    public function test_subscription_event_without_email_reuses_the_existing_stripe_customer_donor(): void
    {
        $invoice = $this->event('evt_invoice_for_customer_match', 'invoice.paid', [
            'id' => 'in_customer_match',
            'status' => 'paid',
            'customer' => 'cus_customer_match',
            'customer_email' => 'matched@example.com',
            'amount_paid' => 2500,
            'currency' => 'usd',
            'parent' => ['subscription_details' => ['subscription' => 'sub_customer_match']],
            'lines' => ['data' => [['pricing' => ['price_details' => ['price' => 'price_customer_match'], 'unit_amount_decimal' => '2500']]]],
        ]);
        $subscription = $this->event('evt_subscription_for_customer_match', 'customer.subscription.updated', [
            'id' => 'sub_customer_match',
            'customer' => 'cus_customer_match',
            'status' => 'active',
            'billing_cycle_anchor' => 1_700_100_000,
            'items' => ['data' => [['price' => ['id' => 'price_customer_match', 'unit_amount' => 2500, 'currency' => 'usd', 'recurring' => ['interval' => 'month', 'interval_count' => 1]]]]],
        ]);

        $this->postJson('/stripe/webhook', $invoice, $this->signature($invoice))->assertOk();
        $this->postJson('/stripe/webhook', $subscription, $this->signature($subscription))->assertOk();

        $this->assertDatabaseCount('donors', 1);
        $this->assertDatabaseHas('donors', ['email' => 'matched@example.com', 'stripe_customer_id' => 'cus_customer_match']);
        $this->assertDatabaseHas('donation_supports', ['stripe_subscription_id' => 'sub_customer_match', 'status' => 'active']);
        $this->assertSame('2023-11-16 02:00:00', DonationSupport::query()->where('stripe_subscription_id', 'sub_customer_match')->value('current_period_ends_at')?->format('Y-m-d H:i:s'));
    }

    public function test_rejects_invalid_signature_and_success_redirect_never_creates_a_donation(): void
    {
        $this->postJson('/stripe/webhook', $this->event('evt_bad', 'invoice.paid', []), ['Stripe-Signature' => 't=1,v1=bad'])
            ->assertBadRequest();
        $this->get('/donate/success?session_id=cs_untrusted')->assertRedirect('/donate/thank-you');
        $this->assertDatabaseCount('donations', 0);
        $this->assertDatabaseCount('stripe_webhook_events', 0);
    }

    public function test_lost_dispute_creates_one_linked_negative_adjustment(): void
    {
        $checkout = $this->event('evt_checkout_disputed', 'checkout.session.completed', [
            'id' => 'cs_disputed', 'mode' => 'payment', 'payment_status' => 'paid',
            'amount_total' => 2500, 'currency' => 'usd', 'payment_intent' => 'pi_disputed',
            'customer_email' => 'dispute@example.com', 'customer_details' => ['name' => 'Dispute Donor'], 'metadata' => [],
        ]);
        $this->postJson('/stripe/webhook', $checkout, $this->signature($checkout))->assertOk();
        Donation::query()->where('stripe_checkout_session_id', 'cs_disputed')->update(['stripe_charge_id' => 'ch_disputed']);

        $dispute = $this->event('evt_dispute_lost', 'charge.dispute.closed', [
            'id' => 'dp_lost', 'charge' => 'ch_disputed', 'status' => 'lost', 'amount' => 2500, 'currency' => 'usd', 'created' => 1_700_000_000,
        ]);
        $this->postJson('/stripe/webhook', $dispute, $this->signature($dispute))->assertOk();
        $this->postJson('/stripe/webhook', $dispute, $this->signature($dispute))->assertOk();

        $this->assertDatabaseHas('donations', ['stripe_dispute_id' => 'dp_lost', 'status' => 'disputed', 'amount_cents' => -2500]);
        $this->assertDatabaseCount('donations', 2);
    }

    /** @return array<string, mixed> */
    private function event(string $id, string $type, array $object): array
    {
        return ['id' => $id, 'object' => 'event', 'api_version' => '2025-01-27.acacia', 'created' => 1_700_000_000, 'livemode' => false, 'type' => $type, 'data' => ['object' => $object]];
    }

    /** @return array<string, string> */
    private function signature(array $payload): array
    {
        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.json_encode($payload, JSON_THROW_ON_ERROR), 'whsec_test_webhook');

        return ['Stripe-Signature' => "t={$timestamp},v1={$signature}"];
    }
}
