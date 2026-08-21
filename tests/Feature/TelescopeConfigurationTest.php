<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class TelescopeConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_filament_users_can_access_telescope_but_guests_cannot(): void
    {
        $user = User::factory()->create();

        $this->assertFalse(Gate::allows('viewTelescope'));

        $this->actingAs($user);

        $this->assertTrue(Gate::allows('viewTelescope'));
    }

    public function test_telescope_uses_syncor_style_selective_production_retention_defaults(): void
    {
        $this->assertFalse((bool) config('telescope.register_all'));
        $this->assertSame(96, config('telescope.prune_hours'));
        $this->assertSame('telescope', config('telescope.path'));
    }
}
