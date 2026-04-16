<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\SiteSettings;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SiteSettingsVisibilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('site_settings')) {
            Schema::create('site_settings', function (Blueprint $table): void {
                $table->id();
                $table->boolean('show_blog')->default(true);
                $table->boolean('show_impact')->default(true);
                $table->string('contact_email')->nullable();
                $table->string('donations_email')->nullable();
                $table->timestamps();
            });

            SiteSettings::query()->create(SiteSettings::defaultAttributes());
        }
    }

    public function test_site_settings_singleton_defaults_exist_after_migration(): void
    {
        $settings = SiteSettings::current();

        $this->assertTrue($settings->show_blog);
        $this->assertTrue($settings->show_impact);
        $this->assertSame('hello@threefoldartists.org', $settings->contact_email);
    }
}
