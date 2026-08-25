<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\ArtistResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtistPortraitCropTest extends TestCase
{
    use RefreshDatabase;

    public function test_artist_create_form_requires_an_editor_selected_three_by_four_portrait_crop(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(ArtistResource::getUrl('create'))
            ->assertOk()
            ->assertSee('Artist portrait')
            ->assertSee('Choose the 3:4 crop that keeps the artist well framed.');
    }
}
