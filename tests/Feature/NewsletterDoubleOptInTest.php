<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\NewsletterSubscriptionConfirmation;
use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NewsletterDoubleOptInTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscribing_creates_a_pending_subscriber_and_sends_a_confirmation_email(): void
    {
        Mail::fake();

        $this->subscribe('artist@example.com')
            ->assertRedirect(route('home'))
            ->assertSessionHas('newsletter_success');

        $subscriber = NewsletterSubscriber::query()->where('email', 'artist@example.com')->firstOrFail();

        $this->assertNull($subscriber->confirmed_at);
        $this->assertNull($subscriber->unsubscribed_at);
        Mail::assertSent(NewsletterSubscriptionConfirmation::class, fn ($mail): bool => $mail->hasTo('artist@example.com'));
    }

    public function test_a_pending_subscriber_is_only_confirmed_after_submitting_the_confirmation_form(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'pending@example.com',
            'confirmed_at' => null,
            'unsubscribed_at' => null,
            'token' => 'confirm-token',
        ]);

        $this->get(route('newsletter.confirm', ['token' => $subscriber->token]))
            ->assertOk()
            ->assertSee('Confirm Subscription');

        $this->withSession(['_token' => 'test-token'])
            ->post(route('newsletter.confirm.process'), [
                '_token' => 'test-token',
                'token' => $subscriber->token,
            ])
            ->assertRedirect(route('newsletter.confirmed'));

        $this->assertNotNull($subscriber->fresh()->confirmed_at);
    }

    private function subscribe(string $email): \Illuminate\Testing\TestResponse
    {
        return $this->withSession(['_token' => 'test-token'])
            ->from(route('home'))
            ->post(route('newsletter.subscribe'), [
                '_token' => 'test-token',
                'cf-turnstile-response' => 'test-token',
                'email' => $email,
                'source' => 'homepage',
            ]);
    }
}
