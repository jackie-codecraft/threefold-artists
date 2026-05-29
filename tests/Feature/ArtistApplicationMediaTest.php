<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\ArtistApplicationResource\Pages\EditArtistApplication;
use App\Filament\Resources\ArtistApplicationResource\Pages\ListArtistApplications;
use App\Mail\ArtistApplicationSubmitted;
use App\Models\ArtistApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class ArtistApplicationMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_media_can_be_previewed_and_downloaded_from_signed_routes(): void
    {
        Storage::fake('local');

        $application = $this->applicationWithMedia();

        $photo = $application->getFirstMedia('photo');
        $resume = $application->getFirstMedia('resume');

        $this->get($application->mediaPreviewUrl('photo'))
            ->assertOk()
            ->assertHeader('content-type', $photo->mime_type);

        $this->get($application->mediaDownloadUrl('resume'))
            ->assertOk()
            ->assertHeader('content-type', $resume->mime_type)
            ->assertHeader('content-disposition', 'attachment; filename=alex-resume.pdf');
    }

    public function test_admin_artist_application_surfaces_media_on_list_and_detail_pages(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $application = $this->applicationWithMedia();

        $this->actingAs($user);

        Livewire::test(ListArtistApplications::class)
            ->assertTableColumnExists('artist_photo');

        Livewire::test(EditArtistApplication::class, ['record' => $application->getRouteKey()])
            ->assertSee('Download photo')
            ->assertSee('Download resume');
    }

    public function test_artist_application_notification_email_includes_media_links_and_attachments(): void
    {
        Storage::fake('local');

        $application = $this->applicationWithMedia();
        $photo = $application->getFirstMedia('photo');
        $resume = $application->getFirstMedia('resume');
        $mail = new ArtistApplicationSubmitted($application);

        $mail
            ->assertSeeInHtml('Download photo')
            ->assertSeeInHtml('Download resume')
            ->assertSeeInHtml($application->mediaPreviewUrl('photo'), false)
            ->assertHasAttachment(Attachment::fromStorageDisk('local', $photo->getPathRelativeToRoot())
                ->as($photo->file_name)
                ->withMime($photo->mime_type))
            ->assertHasAttachment(Attachment::fromStorageDisk('local', $resume->getPathRelativeToRoot())
                ->as($resume->file_name)
                ->withMime($resume->mime_type));
    }

    public function test_standalone_reply_page_surfaces_application_media(): void
    {
        Storage::fake('local');

        $application = $this->applicationWithMedia();

        $this->get(route('artist-application.reply', ['artistApplication' => $application->id]))
            ->assertForbidden();

        $this->get(URL::signedRoute('artist-application.reply', ['artistApplication' => $application->id]))
            ->assertOk()
            ->assertSee('Download photo')
            ->assertSee('Download Resume')
            ->assertSee($application->mediaPreviewUrl('photo'), false)
            ->assertSee($application->mediaDownloadUrl('resume'), false);
    }

    private function applicationWithMedia(): ArtistApplication
    {
        $application = ArtistApplication::create([
            'name' => 'Alex Artist',
            'email' => 'alex@example.com',
            'phone' => '555-0101',
            'discipline' => 'music',
            'status' => 'pending',
            'experience' => 'Community performances',
            'availability' => 'Weekends',
        ]);

        $application
            ->addMedia(UploadedFile::fake()->image('alex.jpg', 800, 1000))
            ->toMediaCollection('photo');

        $application
            ->addMedia(UploadedFile::fake()->create('alex-resume.pdf', 256, 'application/pdf'))
            ->toMediaCollection('resume');

        return $application->refresh();
    }
}
