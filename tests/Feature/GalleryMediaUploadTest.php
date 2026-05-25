<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Forms\Components\GalleryMediaUpload;
use Tests\TestCase;

class GalleryMediaUploadTest extends TestCase
{
    public function test_gallery_media_upload_accepts_images_and_common_video_formats(): void
    {
        $acceptedFileTypes = GalleryMediaUpload::make()->getAcceptedFileTypes();

        $this->assertContains('image/*', $acceptedFileTypes);
        $this->assertContains('video/mp4', $acceptedFileTypes);
        $this->assertContains('video/quicktime', $acceptedFileTypes);
        $this->assertContains('video/webm', $acceptedFileTypes);
    }
}
