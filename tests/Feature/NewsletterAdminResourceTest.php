<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\NewsletterResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterAdminResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_newsletter_create_form_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(NewsletterResource::getUrl('create'))
            ->assertOk()
            ->assertSee('Send to')
            ->assertSee('Custom selection');
    }
}
