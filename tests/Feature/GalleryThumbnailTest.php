<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\GalleryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryThumbnailTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_cards_use_an_optional_editor_crop_while_the_lightbox_keeps_the_original(): void
    {
        Storage::fake('public');

        $item = GalleryItem::query()->create([
            'title' => 'Portrait from the Stage',
            'type' => 'photo',
            'is_active' => true,
        ]);

        $original = $item->addMedia(UploadedFile::fake()->image('full-performance-photo.jpg', 1800, 1200))
            ->toMediaCollection('media', 'public');
        $thumbnail = $item->addMedia(UploadedFile::fake()->image('editor-selected-thumbnail.jpg', 1200, 900))
            ->toMediaCollection('thumbnail', 'public');

        $this->assertSame($thumbnail->getUrl(), $item->thumbnailUrl());

        $response = $this->get(route('gallery'));

        $response->assertOk()
            ->assertSee($thumbnail->getUrl(), false)
            ->assertSee($original->getUrl(), false)
            ->assertSeeInOrder([$original->getUrl(), $thumbnail->getUrl()], false);
    }

    public function test_gallery_cards_fall_back_to_the_original_when_no_thumbnail_crop_exists(): void
    {
        Storage::fake('public');

        $item = GalleryItem::query()->create([
            'title' => 'Original-only image',
            'type' => 'photo',
            'is_active' => true,
        ]);

        $original = $item->addMedia(UploadedFile::fake()->image('original-only.jpg', 1800, 1200))
            ->toMediaCollection('media', 'public');

        $this->assertSame($original->getUrl(), $item->thumbnailUrl());
    }
}
