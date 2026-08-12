<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\PledgeResource;
use App\Models\Pledge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PledgePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_historic_pledges_remain_available_to_the_admin_resource_after_public_collection_is_retired(): void
    {
        $pledge = Pledge::query()->create([
            'name' => 'Historic Supporter',
            'email' => 'historic@example.com',
            'amount' => 250,
            'public_acknowledgment_consent' => true,
            'status' => 'new',
        ]);

        $this->assertSame(Pledge::class, PledgeResource::getModel());
        $this->assertDatabaseHas('pledges', [
            'id' => $pledge->id,
            'email' => 'historic@example.com',
        ]);
        $this->get('/admin/pledges')->assertRedirect('/admin/login');
        $this->get("/admin/pledges/{$pledge->id}/edit")->assertRedirect('/admin/login');
    }
}
