<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\EventResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventAdminResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_create_form_exposes_featured_image_upload(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(EventResource::getUrl('create'))
            ->assertOk()
            ->assertSee('Original featured image')
            ->assertSee('Event card thumbnail crop')
            ->assertSee('The full image above remains untouched.')
            ->assertSee('Post-event recap')
            ->assertSee('Publish in Past Events archive');
    }
}
