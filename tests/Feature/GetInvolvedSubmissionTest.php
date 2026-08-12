<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\ArtistApplicationSubmitted;
use App\Models\ArtistApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class GetInvolvedSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_artist_application_submission_persists_and_redirects(): void
    {
        Mail::fake();
        Storage::fake('local');

        $response = $this->post(route('get-involved.store'), $this->validPayload());

        $response->assertRedirect(route('get-involved.thanks'));

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

        $response = $this->post(route('get-involved.store'), $this->validPayload());

        $response->assertRedirect(route('get-involved.thanks'));

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
        $this->get(route('get-involved.thanks'))
            ->assertOk()
            ->assertSee('Application Received')
            ->assertSee('Thank you for sharing your artistry with Threefold Artists. We’ve received your application and will review it with care. We’ll be in touch soon.')
            ->assertDontSee('Welcome Aboard');
    }

    /**
     * @return array<string, mixed>
     */
    private function validPayload(): array
    {
        return [
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
        ];
    }
}
