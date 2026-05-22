<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Pledge;
use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PledgePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_pledge_page_loads_with_brand_content_and_no_areas_of_interest_form_section(): void
    {
        $response = $this->get(route('pledge'));

        $response->assertOk();
        $response->assertSee('Become a Founding Supporter');
        $response->assertSee('What You Make Possible');
        $response->assertSee('Employ and support working artists');
        $response->assertSee('Founding Supporter Pledge Form');
        $response->assertDontSee('Areas of Interest');
    }

    public function test_pledge_form_accepts_valid_submission(): void
    {
        $response = $this->withSession(['_token' => 'test-token'])
            ->post(route('pledge.store'), $this->validPayload());

        $response->assertRedirect(route('pledge.thanks'));
        $this->assertDatabaseHas(Pledge::class, [
            'name' => 'Jane Supporter',
            'email' => 'jane@example.com',
            'phone' => '555-123-4567',
            'amount' => 250,
            'status' => 'new',
        ]);
    }

    public function test_pledge_form_rejects_honeypot_submissions(): void
    {
        $response = $this->withSession(['_token' => 'test-token'])
            ->from(route('pledge'))
            ->post(route('pledge.store'), $this->validPayload([
                'website' => 'https://spam.example',
            ]));

        $response->assertRedirect(route('pledge'));
        $response->assertSessionHasErrors('website');
        $this->assertDatabaseMissing(Pledge::class, [
            'email' => 'jane@example.com',
            'message' => 'I want to help keep theatre accessible.',
        ]);
    }

    public function test_contact_form_throttle_does_not_block_pledge_submissions(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->withSession(['_token' => 'test-token'])
                ->post(route('contact.store'), [
                    '_token' => 'test-token',
                    'name' => "Contact {$i}",
                    'email' => "contact{$i}@example.com",
                    'subject' => 'Question',
                    'message' => 'I would like to learn more about your performances.',
                    'website' => '',
                    'form_started_at' => (string) now()->subSeconds(10)->timestamp,
                ])
                ->assertRedirect(route('contact.thanks'));
        }

        $this->withSession(['_token' => 'test-token'])
            ->post(route('pledge.store'), $this->validPayload([
                'email' => 'pledge-after-contact@example.com',
            ]))
            ->assertRedirect(route('pledge.thanks'));
    }

    public function test_disabled_pledges_are_hidden_from_navigation_and_return_404(): void
    {
        SiteSettings::query()->updateOrCreate([], [
            'show_pledges' => false,
        ]);

        $homeResponse = $this->get(route('home'));

        $homeResponse->assertOk();
        $homeResponse->assertDontSee(route('pledge'), false);

        $this->get(route('pledge'))->assertNotFound();
        $this->withSession(['_token' => 'test-token'])
            ->post(route('pledge.store'), $this->validPayload())
            ->assertNotFound();
        $this->get(route('pledge.thanks'))->assertNotFound();
    }

    public function test_sitemap_omits_disabled_pledge_url(): void
    {
        SiteSettings::query()->updateOrCreate([], [
            'show_pledges' => false,
        ]);

        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertDontSee('/pledge', false);
    }

    /**
     * @param  array<string, string|int>  $overrides
     * @return array<string, string|int>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            '_token' => 'test-token',
            'name' => 'Jane Supporter',
            'email' => 'jane@example.com',
            'phone' => '555-123-4567',
            'amount' => 250,
            'message' => 'I want to help keep theatre accessible.',
            'pledge_acknowledgment' => '1',
            'website' => '',
            'form_started_at' => (string) now()->subSeconds(10)->timestamp,
        ], $overrides);
    }
}
