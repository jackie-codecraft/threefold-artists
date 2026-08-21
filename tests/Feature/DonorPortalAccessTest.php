<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\DonorAccessLinkMail;
use App\Models\Donation;
use App\Models\DonationSupport;
use App\Models\Donor;
use App\Models\DonorAccessLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Stripe\ApiRequestor;
use Stripe\HttpClient\ClientInterface;
use Tests\TestCase;

class DonorPortalAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        ApiRequestor::setHttpClient(null);

        parent::tearDown();
    }

    #[Test]
    public function it_returns_the_same_response_for_known_and_unknown_emails_but_only_mails_matching_donors(): void
    {
        $donor = Donor::query()->create(['email' => 'supporter@example.com']);
        Mail::fake();

        $known = $this->post(route('donor-access.send'), ['email' => ' SUPPORTER@EXAMPLE.COM ']);
        $unknown = $this->post(route('donor-access.send'), ['email' => 'unknown@example.com']);

        $known->assertRedirect(route('donor-access.request'));
        $unknown->assertRedirect(route('donor-access.request'));
        $this->assertSame($known->getSession()->get('success'), $unknown->getSession()->get('success'));
        Mail::assertSent(DonorAccessLinkMail::class, fn (DonorAccessLinkMail $mail): bool => $mail->hasTo($donor->email));
        Mail::assertSent(DonorAccessLinkMail::class, 1);

        $link = DonorAccessLink::query()->sole();
        $this->assertSame($donor->id, $link->donor_id);
        $this->assertSame($donor->email, $link->email);
        $this->assertSame('portal', $link->purpose);
        $this->assertSame(64, strlen($link->token_hash));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $link->token_hash);
        $this->assertNull($link->used_at);
        $this->assertTrue($link->expires_at->isFuture());
    }

    #[Test]
    public function a_valid_link_is_single_use_and_only_authorizes_its_matching_donor_history(): void
    {
        $donor = Donor::query()->create(['email' => 'supporter@example.com', 'stripe_customer_id' => 'cus_supporter']);
        $otherDonor = Donor::query()->create(['email' => 'other@example.com']);
        Donation::query()->create(['donor_id' => $donor->id, 'donor_email' => $donor->email, 'amount' => '25.00', 'amount_cents' => 2500, 'status' => 'paid', 'paid_at' => now()]);
        Donation::query()->create(['donor_id' => $otherDonor->id, 'donor_email' => $otherDonor->email, 'amount' => '99.00', 'amount_cents' => 9900, 'status' => 'paid', 'paid_at' => now()]);

        $token = random_bytes(64);
        DonorAccessLink::query()->create([
            'donor_id' => $donor->id,
            'email' => $donor->email,
            'token_hash' => hash('sha256', $token),
            'purpose' => 'portal',
            'expires_at' => now()->addMinutes(30),
        ]);
        $url = route('donor-access.consume', ['token' => rtrim(strtr(base64_encode($token), '+/', '-_'), '=')]);

        $this->get($url)->assertRedirect(route('donor-portal'));
        $this->get(route('donor-portal'))
            ->assertOk()
            ->assertSee('$25.00')
            ->assertDontSee('$99.00');
        $this->assertNotNull(DonorAccessLink::query()->sole()->used_at);

        $this->get($url)
            ->assertRedirect(route('donor-access.request'))
            ->assertSessionHas('error');
    }

    #[Test]
    public function portal_clearly_marks_a_subscription_that_is_canceling_at_the_end_of_its_current_period(): void
    {
        $donor = Donor::query()->create(['email' => 'canceling@example.com']);
        DonationSupport::query()->create([
            'donor_id' => $donor->id,
            'stripe_subscription_id' => 'sub_canceling',
            'amount_cents' => 7500,
            'currency' => 'usd',
            'interval' => 'monthly',
            'interval_count' => 1,
            'status' => 'active',
            'cancel_at_period_end' => true,
            'current_period_ends_at' => now()->addMonth(),
        ]);

        $this->withSession(['donor_portal.donor_id' => $donor->id])
            ->get(route('donor-portal'))
            ->assertOk()
            ->assertSee('Cancellation scheduled')
            ->assertSee('No further renewals will be charged.')
            ->assertDontSee('Active — monthly');
    }

    #[Test]
    public function the_stripe_portal_is_created_only_for_the_authorized_donors_customer_and_returns_to_the_portal(): void
    {
        config(['services.stripe.secret' => 'sk_test_portal']);
        $donor = Donor::query()->create(['email' => 'supporter@example.com', 'stripe_customer_id' => 'cus_authorized']);
        $otherDonor = Donor::query()->create(['email' => 'other@example.com', 'stripe_customer_id' => 'cus_other']);
        $this->withSession(['donor_portal.donor_id' => $donor->id]);
        $client = new DonorPortalStripeClient;
        ApiRequestor::setHttpClient($client);

        $this->post(route('donor-portal.billing'))->assertRedirect('https://billing.stripe.test/session');

        $this->assertSame('cus_authorized', $client->params['customer']);
        $this->assertSame(route('donor-portal'), $client->params['return_url']);
        $this->assertNotSame($otherDonor->stripe_customer_id, $client->params['customer']);
    }

    #[Test]
    public function expired_and_unrecognized_links_do_not_establish_donor_access(): void
    {
        $donor = Donor::query()->create(['email' => 'supporter@example.com']);
        DonorAccessLink::query()->create([
            'donor_id' => $donor->id,
            'email' => $donor->email,
            'token_hash' => hash('sha256', str_repeat('a', 64)),
            'purpose' => 'portal',
            'expires_at' => now()->subMinute(),
        ]);

        $this->get(route('donor-access.consume', ['token' => rtrim(strtr(base64_encode(str_repeat('a', 64)), '+/', '-_'), '=')]))
            ->assertRedirect(route('donor-access.request'));
        $this->get(route('donor-portal'))->assertRedirect(route('donor-access.request'));
    }
}

class DonorPortalStripeClient implements ClientInterface
{
    /** @var array<string, mixed> */
    public array $params = [];

    public function request($method, $absUrl, $headers, $params, $hasFile, $apiMode = 'v1'): array
    {
        $this->params = $params;

        return [json_encode(['id' => 'bps_test', 'url' => 'https://billing.stripe.test/session']), 200, []];
    }
}
