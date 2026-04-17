<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteSettingsVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_settings_singleton_defaults_exist_after_migration(): void
    {
        $settings = SiteSettings::current();

        $this->assertTrue($settings->show_blog);
        $this->assertTrue($settings->show_impact);
        $this->assertTrue($settings->show_gallery);
        $this->assertTrue($settings->show_events);
        $this->assertTrue($settings->show_donations);
        $this->assertSame('hello@threefoldartists.org', $settings->contact_email);
    }

    public function test_disabled_gallery_and_events_are_hidden_from_navigation_and_return_404(): void
    {
        SiteSettings::query()->updateOrCreate([], [
            'show_gallery' => false,
            'show_events' => false,
        ]);

        $homeResponse = $this->get(route('home'));

        $homeResponse->assertOk();
        $homeResponse->assertDontSee(route('gallery'), false);
        $homeResponse->assertDontSee(route('events'), false);

        $this->get(route('gallery'))->assertNotFound();
        $this->get(route('events'))->assertNotFound();
    }

    public function test_disabled_donations_are_hidden_from_navigation_and_return_404(): void
    {
        SiteSettings::query()->updateOrCreate([], [
            'show_donations' => false,
        ]);

        $homeResponse = $this->get(route('home'));

        $homeResponse->assertOk();
        $homeResponse->assertDontSee(route('donate'), false);
        $homeResponse->assertDontSee(route('donor-wall'), false);
        $homeResponse->assertDontSee('All donations are tax-deductible.');

        $this->get(route('donate'))->assertNotFound();
        $this->post(route('donate.checkout'), [
            'amount' => 50,
        ])->assertNotFound();
        $this->get(route('donate.success'))->assertNotFound();
        $this->get(route('donate.thanks'))->assertNotFound();
        $this->get(route('donor-wall'))->assertNotFound();
    }

    public function test_sitemap_omits_disabled_gallery_events_and_donations_urls(): void
    {
        SiteSettings::query()->updateOrCreate([], [
            'show_gallery' => false,
            'show_events' => false,
            'show_donations' => false,
        ]);

        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertDontSee('/gallery', false);
        $response->assertDontSee('/events', false);
        $response->assertDontSee('/donate', false);
        $response->assertDontSee('/donor-wall', false);
    }
}
