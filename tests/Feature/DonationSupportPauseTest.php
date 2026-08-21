<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\DonationSupport;
use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Stripe\ApiRequestor;
use Stripe\HttpClient\ClientInterface;
use Tests\TestCase;

class DonationSupportPauseTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        ApiRequestor::setHttpClient(null);
        parent::tearDown();
    }

    #[Test]
    public function a_donor_can_request_a_monthly_pause_and_stripe_receives_void_with_the_correct_resume_time(): void
    {
        config(['services.stripe.secret' => 'sk_test_pause']);
        $donor = Donor::query()->create(['email' => 'supporter@example.com']);
        $endsAt = Carbon::parse('2026-01-31 12:00:00', 'UTC');
        $support = $this->support($donor, ['interval' => 'monthly', 'current_period_ends_at' => $endsAt]);
        $client = new PauseStripeClient;
        ApiRequestor::setHttpClient($client);

        $this->withSession(['donor_portal.donor_id' => $donor->id])
            ->post(route('donor-portal.supports.pause', $support), ['periods' => 2])
            ->assertRedirect(route('donor-portal'))
            ->assertSessionHas('success');

        $this->assertSame('post', strtolower($client->method));
        $this->assertStringContainsString('/v1/subscriptions/sub_pause', $client->url);
        $this->assertSame('void', $client->params['pause_collection']['behavior']);
        $this->assertSame($endsAt->copy()->addMonths(2)->getTimestamp(), $client->params['pause_collection']['resumes_at']);
        $support->refresh();
        $this->assertNull($support->paused_until);
        $this->assertNull($support->pause_resumes_at);
    }

    #[Test]
    public function donor_can_schedule_a_new_monthly_amount_for_the_next_renewal_without_proration(): void
    {
        config(['services.stripe.secret' => 'sk_test_change']);
        $donor = Donor::query()->create(['email' => 'change@example.com']);
        $endsAt = Carbon::parse('2026-02-28 12:00:00', 'UTC');
        $support = $this->support($donor, ['stripe_subscription_id' => 'sub_change', 'stripe_price_id' => 'price_current', 'current_period_ends_at' => $endsAt]);
        $client = new PauseStripeClient;
        ApiRequestor::setHttpClient($client);

        $this->withSession(['donor_portal.donor_id' => $donor->id])
            ->post(route('donor-portal.supports.amount', $support), ['amount' => '25', 'cadence' => 'monthly'])
            ->assertRedirect(route('donor-portal'))
            ->assertSessionHas('success');

        $update = collect($client->requests)->first(fn (array $request): bool => str_ends_with($request['url'], '/v1/subscriptions/sub_change') && array_key_exists('proration_behavior', $request['params']));
        $this->assertNotNull($update);
        $this->assertSame('none', $update['params']['proration_behavior']);
        $this->assertSame('price_new', $update['params']['items'][0]['price']);
        $support->refresh();
        $this->assertSame(2500, $support->pending_amount_cents);
        $this->assertTrue($support->pending_amount_effective_at->equalTo($endsAt));
    }

    #[Test]
    public function donation_amount_changes_only_accept_whole_dollars(): void
    {
        $donor = Donor::query()->create(['email' => 'whole-dollars@example.com']);
        $support = $this->support($donor);
        $client = new PauseStripeClient;
        ApiRequestor::setHttpClient($client);

        $this->withSession(['donor_portal.donor_id' => $donor->id])
            ->from(route('donor-portal'))
            ->post(route('donor-portal.supports.amount', $support), ['amount' => '25.50'])
            ->assertRedirect(route('donor-portal'))
            ->assertSessionHasErrors('amount');

        $this->assertSame([], $client->params);
    }

    #[Test]
    public function inactive_stripe_products_are_replaced_before_scheduling_a_new_donation_price(): void
    {
        config(['services.stripe.secret' => 'sk_test_change']);
        $donor = Donor::query()->create(['email' => 'inactive-product@example.com']);
        $support = $this->support($donor, ['stripe_subscription_id' => 'sub_inactive_product', 'stripe_price_id' => 'price_current']);
        $client = new PauseStripeClient;
        $client->inactiveProduct = true;
        ApiRequestor::setHttpClient($client);

        $this->withSession(['donor_portal.donor_id' => $donor->id])
            ->post(route('donor-portal.supports.amount', $support), ['amount' => '30', 'cadence' => 'monthly'])
            ->assertRedirect(route('donor-portal'));

        $productCreate = collect($client->requests)->first(fn (array $request): bool => str_ends_with($request['url'], '/v1/products') && $request['params'] !== []);
        $subscriptionUpdate = collect($client->requests)->first(fn (array $request): bool => str_ends_with($request['url'], '/v1/subscriptions/sub_inactive_product') && array_key_exists('proration_behavior', $request['params']));
        $this->assertNotNull($productCreate);
        $this->assertSame('price_new', $subscriptionUpdate['params']['items'][0]['price']);
    }

    #[Test]
    public function pause_lengths_are_limited_by_cadence_and_do_not_call_stripe_when_invalid(): void
    {
        config(['services.stripe.secret' => 'sk_test_pause']);
        $donor = Donor::query()->create(['email' => 'quarterly@example.com']);
        $support = $this->support($donor, ['interval' => 'quarterly']);
        $client = new PauseStripeClient;
        ApiRequestor::setHttpClient($client);

        $this->withSession(['donor_portal.donor_id' => $donor->id])
            ->from(route('donor-portal'))
            ->post(route('donor-portal.supports.pause', $support), ['periods' => 3])
            ->assertRedirect(route('donor-portal'))
            ->assertSessionHasErrors('periods');

        $this->assertSame([], $client->params);
    }

    #[Test]
    public function donor_session_cannot_pause_another_donors_support_or_ineligible_support(): void
    {
        config(['services.stripe.secret' => 'sk_test_pause']);
        $donor = Donor::query()->create(['email' => 'supporter@example.com']);
        $other = Donor::query()->create(['email' => 'other@example.com']);
        $otherSupport = $this->support($other);
        $endingSupport = $this->support($donor, ['cancel_at_period_end' => true]);
        $client = new PauseStripeClient;
        ApiRequestor::setHttpClient($client);

        $this->withSession(['donor_portal.donor_id' => $donor->id])
            ->post(route('donor-portal.supports.pause', $otherSupport), ['periods' => 1])
            ->assertNotFound();
        $this->withSession(['donor_portal.donor_id' => $donor->id])
            ->from(route('donor-portal'))
            ->post(route('donor-portal.supports.pause', $endingSupport), ['periods' => 1])
            ->assertRedirect(route('donor-portal'))
            ->assertSessionHasErrors('support');

        $this->assertSame([], $client->params);
    }

    private function support(Donor $donor, array $attributes = []): DonationSupport
    {
        return DonationSupport::query()->create([
            'donor_id' => $donor->id, 'stripe_subscription_id' => 'sub_pause'.DonationSupport::query()->count(),
            'amount_cents' => 1000, 'currency' => 'usd', 'interval' => 'monthly', 'interval_count' => 1,
            'status' => 'active', 'cancel_at_period_end' => false, 'current_period_ends_at' => Carbon::parse('2026-02-28 12:00:00', 'UTC'),
            ...$attributes,
        ]);
    }
}

class PauseStripeClient implements ClientInterface
{
    /** @var list<array{url: string, params: array<string, mixed>}> */
    public array $requests = [];

    public bool $inactiveProduct = false;

    public array $params = [];

    public string $method = '';

    public string $url = '';

    public function request($method, $absUrl, $headers, $params, $hasFile, $apiMode = 'v1'): array
    {
        $this->method = $method;
        $this->url = $absUrl;
        $this->params = $params;
        $this->requests[] = ['url' => $absUrl, 'params' => $params];

        if (str_ends_with($absUrl, '/v1/prices/price_current')) {
            return [json_encode(['id' => 'price_current', 'product' => 'prod_donations']), 200, []];
        }

        if (str_ends_with($absUrl, '/v1/products/prod_donations')) {
            return [json_encode(['id' => 'prod_donations', 'active' => ! $this->inactiveProduct]), 200, []];
        }

        if (str_ends_with($absUrl, '/v1/products') && strtolower($method) === 'post') {
            return [json_encode(['id' => 'prod_replacement', 'active' => true]), 200, []];
        }

        if (str_contains($absUrl, '/v1/subscriptions/') && strtolower($method) === 'get') {
            return [json_encode(['id' => 'sub_change', 'items' => ['data' => [['id' => 'si_change']]]]), 200, []];
        }

        if (str_ends_with($absUrl, '/v1/prices') && strtolower($method) === 'post') {
            return [json_encode(['id' => 'price_new']), 200, []];
        }

        return [json_encode(['id' => 'sub_pause', 'object' => 'subscription']), 200, []];
    }
}
