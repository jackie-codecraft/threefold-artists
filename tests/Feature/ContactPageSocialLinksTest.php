<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPageSocialLinksTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_hides_social_section_when_no_social_urls_are_configured(): void
    {
        SiteSettings::query()->updateOrCreate([], [
            'facebook_url' => null,
            'instagram_url' => null,
            'youtube_url' => null,
            'tiktok_url' => null,
        ]);

        $response = $this->get(route('contact'));

        $response->assertOk();
        $response->assertDontSee('Social');
        $response->assertDontSee('Facebook');
        $response->assertDontSee('Instagram');
        $response->assertDontSee('YouTube');
        $response->assertDontSee('TikTok');
    }

    public function test_contact_page_only_shows_configured_social_links(): void
    {
        SiteSettings::query()->updateOrCreate([], [
            'facebook_url' => 'https://facebook.com/threefoldartists',
            'instagram_url' => null,
            'youtube_url' => 'https://youtube.com/@threefoldartists',
            'tiktok_url' => '',
        ]);

        $response = $this->get(route('contact'));

        $response->assertOk();
        $response->assertSee('Social');
        $response->assertSee('Facebook');
        $response->assertSee('https://facebook.com/threefoldartists', false);
        $response->assertSee('YouTube');
        $response->assertSee('https://youtube.com/@threefoldartists', false);
        $response->assertDontSee('Instagram');
        $response->assertDontSee('TikTok');
        $response->assertDontSee('href="#"', false);
    }
}
