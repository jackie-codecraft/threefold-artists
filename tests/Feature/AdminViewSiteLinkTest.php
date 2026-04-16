<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminViewSiteLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_view_site_link_in_topbar(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk()
            ->assertSee('View Site')
            ->assertSee('href="' . route('home') . '"', false)
            ->assertSee('target="_blank"', false);
    }
}
