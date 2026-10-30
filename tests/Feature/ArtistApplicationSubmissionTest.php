<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\ArtistApplicationSubmitted;
use App\Models\ArtistApplication;
use App\Support\UploadLimits;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class ArtistApplicationSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_artist_application_submission_persists_and_redirects(): void
    {
        Mail::fake();
        Storage::fake('local');

        $response = $this->withSession(['_token' => 'test-token'])
            ->post(route('artist-application.store'), array_merge($this->validPayload(), ['_token' => 'test-token']));

        $response->assertRedirect(route('artist-application.thanks'));

        $this->assertDatabaseHas('artist_applications', [
            'email' => 'alex@example.com',
            'name' => 'Alex Artist',
            'discipline' => 'music',
        ]);

        $application = ArtistApplication::query()->where('email', 'alex@example.com')->firstOrFail();

        $this->assertNotNull($application->getFirstMedia('photo'));
        $this->assertNotNull($application->getFirstMedia('resume'));
        $this->assertSame('local', $application->getFirstMedia('photo')->disk);
        $this->assertSame('local', $application->getFirstMedia('resume')->disk);
        $this->assertCount(2, $application->getMedia('supporting_media'));
        $this->assertSame(['alex-performance-reel.mp4', 'alex-song.mp3'], $application->getMedia('supporting_media')->pluck('file_name')->all());

        Mail::assertSent(ArtistApplicationSubmitted::class);
    }

    public function test_public_artist_application_submission_still_succeeds_when_mail_delivery_fails(): void
    {
        Log::spy();
        Storage::fake('local');

        Mail::shouldReceive('to')->once()->with(config('mail.from.address'))->andReturnSelf()->ordered();
        Mail::shouldReceive('send')->once()->withArgs(fn ($mailable) => $mailable instanceof ArtistApplicationSubmitted)->andThrow(new RuntimeException('Resend misconfigured'))->ordered();
        Mail::shouldReceive('mailer')->once()->with('log')->andReturnSelf()->ordered();
        Mail::shouldReceive('to')->once()->with(config('mail.from.address'))->andReturnSelf()->ordered();
        Mail::shouldReceive('send')->once()->withArgs(fn ($mailable) => $mailable instanceof ArtistApplicationSubmitted)->andReturnNull()->ordered();

        $response = $this->withSession(['_token' => 'test-token'])
            ->post(route('artist-application.store'), array_merge($this->validPayload(), ['_token' => 'test-token']));

        $response->assertRedirect(route('artist-application.thanks'));

        $application = ArtistApplication::query()->where('email', 'alex@example.com')->first();

        $this->assertNotNull($application);

        Log::shouldHaveReceived('warning')->once()->withArgs(function (string $message, array $context) use ($application): bool {
            return $message === 'Artist application notification failed to send.'
                && $context['artist_application_id'] === $application->id
                && $context['mail_to'] === config('mail.from.address')
                && $context['exception'] instanceof RuntimeException;
        });
    }

    public function test_artist_application_thank_you_page_confirms_receipt_without_implying_acceptance(): void
    {
        $this->get(route('artist-application.thanks'))
            ->assertOk()
            ->assertSee('Application Received')
            ->assertSee('Thank you for sharing your artistry with Threefold Artists. We’ve received your application and will review it with care. We’ll be in touch soon.')
            ->assertDontSee('Welcome Aboard');
    }

    public function test_artist_can_share_a_demo_video_link_instead_of_uploading(): void
    {
        Mail::fake();
        Storage::fake('local');

        $payload = $this->validPayload(['demo_video_url' => 'https://youtu.be/dQw4w9WgXcQ']);
        unset($payload['supporting_media']);

        $this->withSession(['_token' => 'test-token'])
            ->post(route('artist-application.store'), array_merge($payload, ['_token' => 'test-token']))
            ->assertRedirect(route('artist-application.thanks'));

        $application = ArtistApplication::query()->where('email', 'alex@example.com')->firstOrFail();
        $this->assertSame('https://youtu.be/dQw4w9WgXcQ', $application->demo_video_url);

        $html = (new ArtistApplicationSubmitted($application))->render();
        $this->assertStringContainsString('href="https://youtu.be/dQw4w9WgXcQ"', $html);
    }

    public function test_demo_video_link_must_be_a_web_url(): void
    {
        Storage::fake('local');

        foreach (['javascript:alert(1)', 'not a link', 'ftp://example.com/video.mp4'] as $badUrl) {
            $this->withSession(['_token' => 'test-token'])
                ->from(route('artist-application.create'))
                ->post(route('artist-application.store'), array_merge($this->validPayload(['demo_video_url' => $badUrl]), ['_token' => 'test-token']))
                ->assertSessionHasErrors('demo_video_url');
        }

        $this->assertDatabaseCount('artist_applications', 0);
    }

    public function test_oversized_supporting_media_is_rejected_by_the_app(): void
    {
        Storage::fake('local');

        $payload = $this->validPayload([
            'supporting_media' => [UploadedFile::fake()->create('huge-reel.mov', UploadLimits::SUPPORTING_MEDIA_KB + 1, 'video/quicktime')],
        ]);

        $this->withSession(['_token' => 'test-token'])
            ->from(route('artist-application.create'))
            ->post(route('artist-application.store'), array_merge($payload, ['_token' => 'test-token']))
            ->assertSessionHasErrors('supporting_media.0');

        $this->assertDatabaseCount('artist_applications', 0);
    }

    public function test_form_explains_video_link_and_ships_upload_limits_to_the_browser(): void
    {
        $response = $this->get(route('artist-application.create'))
            ->assertOk()
            ->assertSee('name="demo_video_url"', false)
            ->assertSee('The best way to share a performance video.')
            ->assertSee('If your video is too large to upload below, share it here instead.')
            ->assertSee('Video too large?');

        $limits = UploadLimits::forBrowser();
        $this->assertSame(20 * 1024 * 1024, $limits['media']);
        $this->assertSame(5 * 1024 * 1024, $limits['photo']);
        $this->assertSame(10 * 1024 * 1024, $limits['resume']);
        $response->assertSee('handleSubmit($event)', false);
    }

    public function test_request_limit_tracks_post_max_size_with_headroom(): void
    {
        $this->assertSame(127 * 1024 * 1024, UploadLimits::requestBytes('128M'));
        $this->assertSame(99 * 1024 * 1024, UploadLimits::requestBytes('100M'));
        $this->assertSame(1023 * 1024 * 1024, UploadLimits::requestBytes('1G'));
        $this->assertNull(UploadLimits::requestBytes('0'));

        $largestValidApplicationKb = UploadLimits::PHOTO_KB + UploadLimits::RESUME_KB
            + UploadLimits::SUPPORTING_MEDIA_KB * UploadLimits::SUPPORTING_MEDIA_MAX_FILES;
        $this->assertLessThanOrEqual(UploadLimits::requestBytes('128M'), $largestValidApplicationKb * 1024,
            'A fully loaded, valid application must fit under a 128 MB server limit.');
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Alex Artist',
            'email' => 'alex@example.com',
            'phone' => '555-0101',
            'discipline' => 'music',
            'experience' => 'Community performances',
            'bio' => 'Singer and teaching artist',
            'availability' => 'Weekends',
            'photo' => UploadedFile::fake()->image('alex.jpg', 800, 1000),
            'resume' => UploadedFile::fake()->create('alex-resume.pdf', 256, 'application/pdf'),
            'supporting_media' => [
                UploadedFile::fake()->create('alex-performance-reel.mp4', 1024, 'video/mp4'),
                UploadedFile::fake()->create('alex-song.mp3', 512, 'audio/mpeg'),
            ],
            'cf-turnstile-response' => 'test-token',
        ], $overrides);
    }
}
