<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Pages\SiteSettingsPage;
use App\Models\ImpactMetric;
use App\Models\SiteSettings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SiteSettingsVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_settings_singleton_defaults_exist_after_migration(): void
    {
        $settings = SiteSettings::current();

        $this->assertTrue($settings->show_blog);
        $this->assertTrue($settings->show_impact);
        $this->assertTrue($settings->show_impact_metrics);
        $this->assertTrue($settings->show_gallery);
        $this->assertTrue($settings->show_events);
        $this->assertTrue($settings->show_donations);
        $this->assertTrue($settings->show_pledges);
        $this->assertSame('hello@threefoldartists.org', $settings->contact_email);
    }

    public function test_public_email_links_use_configured_contact_email(): void
    {
        SiteSettings::query()->updateOrCreate([], [
            'contact_email' => 'admin@example.org',
        ]);

        $homeResponse = $this->get(route('home'));

        $homeResponse->assertOk();
        $homeResponse->assertSee('mailto:admin@example.org', false);
        $homeResponse->assertSee('admin@example.org');
        $homeResponse->assertDontSee('mailto:hello@threefoldartists.org', false);

        $pressResponse = $this->get(route('press-kit'));

        $pressResponse->assertOk();
        $pressResponse->assertSee('mailto:admin@example.org', false);
        $pressResponse->assertDontSee('mailto:hello@threefoldartists.org', false);
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

    public function test_disabled_impact_metrics_are_hidden_from_home_and_impact_pages(): void
    {
        ImpactMetric::query()->create([
            'label' => 'First Live Performance',
            'value' => '1',
            'sort_order' => 1,
        ]);

        SiteSettings::query()->updateOrCreate([], [
            'show_impact_metrics' => false,
        ]);

        $homeResponse = $this->get(route('home'));
        $impactResponse = $this->get(route('impact'));

        $homeResponse->assertOk();
        $homeResponse->assertDontSee('By the Numbers');
        $homeResponse->assertDontSee('First Live Performance');

        $impactResponse->assertOk();
        $impactResponse->assertDontSee('First Live Performance');
    }

    public function test_admin_can_control_impact_metrics_visibility(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(SiteSettingsPage::getUrl())
            ->assertOk()
            ->assertSee('Show impact metrics');

        Livewire::actingAs($user)
            ->test(SiteSettingsPage::class)
            ->assertFormFieldExists('show_impact_metrics')
            ->fillForm([
                'show_impact_metrics' => false,
            ])
            ->call('save');

        $this->assertFalse(SiteSettings::current()->show_impact_metrics);
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
