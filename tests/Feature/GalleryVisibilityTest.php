<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\GalleryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_page_only_shows_active_gallery_items(): void
    {
        GalleryItem::query()->create([
            'title' => 'Visible Gallery Item',
            'type' => 'photo',
            'is_active' => true,
        ]);

        GalleryItem::query()->create([
            'title' => 'Hidden Gallery Item',
            'type' => 'photo',
            'is_active' => false,
        ]);

        $response = $this->get(route('gallery'));

        $response->assertOk();
        $response->assertViewHas('items', fn ($items) => $items->pluck('title')->all() === ['Visible Gallery Item']);
        $response->assertSee('Visible Gallery Item');
    }
}
