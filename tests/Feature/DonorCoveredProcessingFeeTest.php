<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\DonationReceipt;
use App\Models\Donation;
use App\Models\DonationSupport;
use App\Models\Donor;
use App\Support\ProcessingFee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Stripe\ApiRequestor;
use Tests\TestCase;

class DonorCoveredProcessingFeeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.stripe.webhook_secret' => 'whsec_test_webhook']);
    }

    protected function tearDown(): void
    {
        ApiRequestor::setHttpClient(null);
        parent::tearDown();
    }

    #[Test]
    public function the_fee_grosses_up_so_the_full_gift_arrives_after_stripe_fees(): void
    {
        $this->assertSame(330, ProcessingFee::forGift(10000)); // $100 -> $103.30 at 2.9% + 30¢
        $this->assertSame(106, ProcessingFee::forGift(2500));
        $this->assertSame(0, ProcessingFee::forGift(0));

        foreach ([100, 1250, 2500, 10000, 123456] as $gift) {
            $total = $gift + ProcessingFee::forGift($gift);
            $net = $total - (int) round($total * 0.029) - 30;
            $this->assertGreaterThanOrEqual($gift, $net, "gift {$gift} nets {$net}");
        }

        config(['donations.processing_fee.percent' => 2.2]);
        $this->assertSame(256, ProcessingFee::forGift(10000)); // nonprofit rate: $102.56
    }

    #[Test]
    public function donate_page_offers_an_unchecked_fee_coverage_opt_in(): void
    {
        $this->get(route('donate'))
            ->assertOk()
            ->assertSee('to help cover processing fees')
            ->assertSee('name="cover_processing_fees"', false)
            ->assertSee('2.9% + 30¢', false)
            ->assertSee('coverFees: false', false);
    }

    #[Test]
    public function one_time_checkout_charges_gift_plus_fee_when_the_donor_opts_in(): void
    {
        config(['services.stripe.secret' => 'sk_test_checkout']);
        $client = new CapturingStripeClient;
        ApiRequestor::setHttpClient($client);

        $this->post(route('donate.checkout'), [
            'amount' => 100, 'donor_email' => 'covers@example.com', 'donation_type' => 'one-time',
            'is_anonymous' => '0', 'public_recognition_consent' => '0', 'cover_processing_fees' => '1',
        ])->assertRedirect('https://checkout.stripe.test/session/cs_test_donation');

        $item = $client->params['line_items'];
        $this->assertCount(1, $item);
        $this->assertSame(10330, $item[0]['price_data']['unit_amount']);
        $this->assertSame('Includes a $100.00 gift plus $3.30 to cover processing fees', $item[0]['price_data']['product_data']['description']);
        $this->assertSame('10000', $client->params['metadata']['gift_amount_cents']);
        $this->assertSame('330', $client->params['metadata']['processing_fee_cents']);
    }

    #[Test]
    public function checkout_is_unchanged_when_the_donor_does_not_opt_in(): void
    {
        config(['services.stripe.secret' => 'sk_test_checkout']);
        $client = new CapturingStripeClient;
        ApiRequestor::setHttpClient($client);

        $this->post(route('donate.checkout'), [
            'amount' => 100, 'donor_email' => 'plain@example.com', 'donation_type' => 'monthly',
            'is_anonymous' => '0', 'public_recognition_consent' => '0', 'cover_processing_fees' => '0',
        ])->assertRedirect();

        $this->assertSame(10000, $client->params['line_items'][0]['price_data']['unit_amount']);
        $this->assertArrayNotHasKey('description', $client->params['line_items'][0]['price_data']['product_data']);
        $this->assertSame('0', $client->params['subscription_data']['metadata']['processing_fee_cents']);
    }

    #[Test]
    public function checkout_webhook_records_the_covered_fee_and_receipt_mentions_it(): void
    {
        Mail::fake();
        $payload = $this->event('evt_fee_checkout', 'checkout.session.completed', [
            'id' => 'cs_fee', 'mode' => 'payment', 'payment_status' => 'paid', 'amount_total' => 10330,
            'currency' => 'usd', 'payment_intent' => 'pi_fee', 'customer_email' => 'covers@example.com',
            'metadata' => ['is_anonymous' => '0', 'gift_amount_cents' => '10000', 'processing_fee_cents' => '330'],
        ]);

        $this->postJson('/stripe/webhook', $payload, $this->signature($payload))->assertOk();

        $donation = Donation::query()->where('stripe_checkout_session_id', 'cs_fee')->sole();
        $this->assertSame(10330, $donation->amount_cents);
        $this->assertSame(330, $donation->processing_fee_cents);
        Mail::assertSent(DonationReceipt::class, fn (DonationReceipt $mail): bool => str_contains($mail->render(), 'Includes $3.30 you added to cover processing fees')
            && str_contains($mail->render(), '$103.30'));
    }

    #[Test]
    public function stale_fee_metadata_that_does_not_match_the_charge_is_not_recorded(): void
    {
        Mail::fake();
        $payload = $this->event('evt_fee_stale', 'checkout.session.completed', [
            'id' => 'cs_stale', 'mode' => 'payment', 'payment_status' => 'paid', 'amount_total' => 5000,
            'currency' => 'usd', 'customer_email' => 'stale@example.com',
            'metadata' => ['gift_amount_cents' => '10000', 'processing_fee_cents' => '330'],
        ]);

        $this->postJson('/stripe/webhook', $payload, $this->signature($payload))->assertOk();

        $this->assertNull(Donation::query()->where('stripe_checkout_session_id', 'cs_stale')->sole()->processing_fee_cents);
    }

    #[Test]
    public function recurring_invoice_records_the_fee_from_subscription_metadata_and_flags_the_support(): void
    {
        Mail::fake();
        $payload = $this->event('evt_fee_invoice', 'invoice.paid', [
            'id' => 'in_fee', 'status' => 'paid', 'customer' => 'cus_fee', 'customer_email' => 'monthly@example.com',
            'amount_paid' => 2606, 'currency' => 'usd', 'metadata' => [],
            'parent' => ['subscription_details' => ['subscription' => 'sub_fee', 'metadata' => [
                'donor_name' => 'Monthly Donor', 'is_anonymous' => '1', 'gift_amount_cents' => '2500', 'processing_fee_cents' => '106',
            ]]],
            'lines' => ['data' => [[
                'currency' => 'usd',
                'pricing' => ['price_details' => ['price' => 'price_fee'], 'unit_amount_decimal' => '2606'],
                'parent' => ['subscription_item_details' => ['subscription' => 'sub_fee']],
            ]]],
        ]);

        $this->postJson('/stripe/webhook', $payload, $this->signature($payload))->assertOk();

        $donation = Donation::query()->where('stripe_invoice_id', 'in_fee')->sole();
        $this->assertSame(106, $donation->processing_fee_cents);
        $this->assertSame('Monthly Donor', $donation->donor_name);
        $this->assertTrue($donation->is_anonymous);
        $this->assertTrue(DonationSupport::query()->where('stripe_subscription_id', 'sub_fee')->sole()->covers_processing_fees);
    }

    #[Test]
    public function portal_amount_change_with_fee_coverage_grosses_up_the_new_price_and_metadata(): void
    {
        config(['services.stripe.secret' => 'sk_test_change']);
        $donor = Donor::query()->create(['email' => 'change@example.com']);
        $support = DonationSupport::query()->create([
            'donor_id' => $donor->id, 'stripe_subscription_id' => 'sub_change', 'stripe_price_id' => 'price_current',
            'amount_cents' => 1000, 'currency' => 'usd', 'interval' => 'monthly', 'interval_count' => 1,
            'status' => 'active', 'cancel_at_period_end' => false, 'covers_processing_fees' => true,
            'current_period_ends_at' => Carbon::parse('2026-02-28 12:00:00', 'UTC'),
        ]);
        $client = new PauseStripeClient;
        ApiRequestor::setHttpClient($client);

        $this->withSession(['donor_portal.donor_id' => $donor->id])
            ->get(route('donor-portal'))
            ->assertSee('Also cover processing fees')
            ->assertSee('coverFees: true', false);

        $this->withSession(['donor_portal.donor_id' => $donor->id])
            ->post(route('donor-portal.supports.amount', $support), ['amount' => '25', 'cadence' => 'monthly', 'cover_processing_fees' => '1'])
            ->assertRedirect(route('donor-portal'));

        $price = collect($client->requests)->first(fn (array $r): bool => str_ends_with($r['url'], '/v1/prices') && isset($r['params']['unit_amount']));
        $this->assertSame(2606, $price['params']['unit_amount']);
        $update = collect($client->requests)->first(fn (array $r): bool => str_ends_with($r['url'], '/v1/subscriptions/sub_change') && isset($r['params']['metadata']));
        $this->assertSame(['gift_amount_cents' => '2500', 'processing_fee_cents' => '106'], $update['params']['metadata']);
        $this->assertSame(2606, $support->refresh()->pending_amount_cents);
    }

    private function event(string $id, string $type, array $object): array
    {
        return ['id' => $id, 'object' => 'event', 'api_version' => '2025-01-27.acacia', 'created' => time(), 'livemode' => false, 'type' => $type, 'data' => ['object' => $object]];
    }

    private function signature(array $payload): array
    {
        $timestamp = time();
        $signedPayload = $timestamp.'.'.json_encode($payload);

        return ['Stripe-Signature' => 't='.$timestamp.',v1='.hash_hmac('sha256', $signedPayload, 'whsec_test_webhook')];
    }
}
