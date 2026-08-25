<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Forms\Components\GalleryMediaUpload;
use App\Filament\Resources\GalleryItemResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryMediaUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_media_upload_accepts_images_and_common_video_formats(): void
    {
        $acceptedFileTypes = GalleryMediaUpload::make()->getAcceptedFileTypes();

        $this->assertContains('image/*', $acceptedFileTypes);
        $this->assertContains('video/mp4', $acceptedFileTypes);
        $this->assertContains('video/quicktime', $acceptedFileTypes);
        $this->assertContains('video/webm', $acceptedFileTypes);
    }

    public function test_gallery_thumbnail_upload_is_a_photo_only_editor_selected_four_by_three_crop(): void
    {
        $thumbnail = GalleryMediaUpload::thumbnail();

        $this->assertSame(['image/*'], $thumbnail->getAcceptedFileTypes());
        $this->assertSame('4:3', $thumbnail->getImageAspectRatio());
        $this->assertTrue($thumbnail->shouldAutomaticallyOpenImageEditorForAspectRatio());
        $this->assertTrue($thumbnail->hasImageEditor());
    }

    public function test_gallery_create_form_exposes_original_media_and_thumbnail_crop_uploads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(GalleryItemResource::getUrl('create'))
            ->assertOk()
            ->assertSee('Original media')
            ->assertSee('Gallery thumbnail crop')
            ->assertSee('The full image above remains untouched.');
    }
}
