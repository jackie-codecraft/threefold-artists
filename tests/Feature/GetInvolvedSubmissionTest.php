<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\ArtistApplicationSubmitted;
use App\Models\ArtistApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class GetInvolvedSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_artist_application_submission_persists_and_redirects(): void
    {
        Mail::fake();

        $response = $this->post(route('get-involved.store'), $this->validPayload());

        $response->assertRedirect(route('get-involved.thanks'));

        $this->assertDatabaseHas('artist_applications', [
            'email' => 'alex@example.com',
            'name' => 'Alex Artist',
            'discipline' => 'music',
        ]);

        Mail::assertSent(ArtistApplicationSubmitted::class);
    }

    public function test_public_artist_application_submission_still_succeeds_when_mail_delivery_fails(): void
    {
        Log::spy();

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

    /**
     * @return array<string, string>
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
        ];
    }
}
