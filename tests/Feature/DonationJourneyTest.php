<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Pledge;
use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Stripe\ApiRequestor;
use Stripe\HttpClient\ClientInterface;
use Tests\TestCase;

class DonationJourneyTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        ApiRequestor::setHttpClient(null);

        parent::tearDown();
    }

    public function test_legacy_pledge_url_permanently_redirects_to_donate_without_exposing_a_public_form(): void
    {
        Pledge::query()->create([
            'name' => 'Historic Supporter',
            'email' => 'historic@example.com',
            'amount' => 100,
            'status' => 'new',
        ]);

        $this->get('/pledge')->assertRedirect('/donate', 301);
        $this->post('/pledge')->assertMethodNotAllowed();
        $this->get('/pledge/thank-you')->assertNotFound();
        $this->assertDatabaseHas('pledges', ['email' => 'historic@example.com']);
    }

    public function test_donation_page_has_all_supported_cadences_and_requires_email_with_an_explicit_anonymous_preference(): void
    {
        $response = $this->get(route('donate'));

        $response->assertOk();
        $response->assertSee('One-Time');
        $response->assertSee('Monthly');
        $response->assertSee('Quarterly');
        $response->assertSee('Annual');
        $response->assertSee('Email <span class="text-curtain-red">(required)</span>', false);
        $response->assertSee('Keep my donation anonymous on public recognition surfaces.');
        $response->assertDontSee('501(c)(3)', false);
        $response->assertDontSee('tax-deductible', false);
        $response->assertDontSee('EIN', false);
        $response->assertDontSee('Pledge', false);
    }

    public function test_public_navigation_and_sitemap_only_advertise_donations(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('donate'), false)
            ->assertDontSee('/pledge', false)
            ->assertDontSee('Pledge', false);

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertSee('/donate', false)
            ->assertDontSee('/pledge', false);
    }

    public function test_checkout_requires_an_email_and_explicit_anonymous_preference(): void
    {
        config(['services.stripe.secret' => 'sk_test_checkout']);

        $this->post(route('donate.checkout'), [
            'amount' => 25,
            'donation_type' => 'monthly',
            'is_anonymous' => '0',
        ])->assertSessionHasErrors('donor_email');

        $this->post(route('donate.checkout'), [
            'amount' => 25,
            'donor_email' => 'supporter@example.com',
            'donation_type' => 'monthly',
        ])->assertSessionHasErrors('is_anonymous');
    }

    #[DataProvider('recurringCadences')]
    public function test_checkout_sends_the_exact_stripe_recurring_interval(string $cadence, array $expectedRecurring): void
    {
        config(['services.stripe.secret' => 'sk_test_checkout']);
        $client = new CapturingStripeClient;
        ApiRequestor::setHttpClient($client);

        $this->post(route('donate.checkout'), [
            'amount' => '12.50',
            'donor_name' => 'Supporter Name',
            'donor_email' => 'supporter@example.com',
            'donation_type' => $cadence,
            'is_anonymous' => '1',
        ])->assertRedirect('https://checkout.stripe.test/session/cs_test_donation');

        $params = $client->params;
        $this->assertSame('subscription', $params['mode']);
        $this->assertSame(1250, $params['line_items'][0]['price_data']['unit_amount']);
        $this->assertSame($expectedRecurring, $params['line_items'][0]['price_data']['recurring']);
        $this->assertSame('supporter@example.com', $params['customer_email']);
        $this->assertSame('Supporter Name', $params['metadata']['donor_name']);
        $this->assertSame('1', $params['metadata']['is_anonymous']);
        $this->assertDatabaseCount('donations', 0);
    }

    public static function recurringCadences(): array
    {
        return [
            'monthly' => ['monthly', ['interval' => 'month']],
            'quarterly' => ['quarterly', ['interval' => 'month', 'interval_count' => 3]],
            'annual' => ['annual', ['interval' => 'year']],
        ];
    }

    public function test_one_time_checkout_uses_payment_mode_without_a_recurring_price(): void
    {
        config(['services.stripe.secret' => 'sk_test_checkout']);
        $client = new CapturingStripeClient;
        ApiRequestor::setHttpClient($client);

        $this->post(route('donate.checkout'), [
            'amount' => 20,
            'donor_email' => 'supporter@example.com',
            'donation_type' => 'one-time',
            'is_anonymous' => '0',
        ])->assertRedirect('https://checkout.stripe.test/session/cs_test_donation');

        $this->assertSame('payment', $client->params['mode']);
        $this->assertArrayNotHasKey('recurring', $client->params['line_items'][0]['price_data']);
        $this->assertSame('0', $client->params['metadata']['is_anonymous']);
        $this->assertDatabaseCount('donations', 0);
    }
}

class CapturingStripeClient implements ClientInterface
{
    /** @var array<string, mixed> */
    public array $params = [];

    public function request($method, $absUrl, $headers, $params, $hasFile, $apiMode = 'v1'): array
    {
        $this->params = $params;

        return [json_encode(['id' => 'cs_test_donation', 'url' => 'https://checkout.stripe.test/session/cs_test_donation']), 200, []];
    }
}
