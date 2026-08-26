<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ContactFormSpamProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_accepts_a_normal_human_submission(): void
    {
        Mail::fake();

        $response = $this->postContact($this->validPayload());

        $response->assertRedirect(route('contact.thanks'));
        $this->assertDatabaseHas(ContactMessage::class, [
            'name' => 'Jane Artist',
            'email' => 'jane@example.com',
            'subject' => 'Booking question',
        ]);
        Mail::assertSent(ContactMessageReceived::class);
    }

    public function test_contact_form_rejects_honeypot_submissions(): void
    {
        Mail::fake();

        $response = $this->postContact($this->validPayload([
            'website' => 'https://spam.example',
        ]), route('contact'));

        $response->assertRedirect(route('contact'));
        $response->assertSessionHasErrors('website');
        $this->assertDatabaseCount(ContactMessage::class, 0);
        Mail::assertNothingSent();
    }

    public function test_contact_form_rejects_submissions_that_arrive_too_quickly(): void
    {
        Mail::fake();

        $response = $this->postContact($this->validPayload([
            'form_started_at' => (string) now()->timestamp,
        ]), route('contact'));

        $response->assertRedirect(route('contact'));
        $response->assertSessionHasErrors('form_started_at');
        $this->assertDatabaseCount(ContactMessage::class, 0);
        Mail::assertNothingSent();
    }

    public function test_contact_form_rejects_link_heavy_messages(): void
    {
        Mail::fake();

        $response = $this->postContact($this->validPayload([
            'message' => 'Please visit https://one.example https://two.example https://three.example',
        ]), route('contact'));

        $response->assertRedirect(route('contact'));
        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount(ContactMessage::class, 0);
        Mail::assertNothingSent();
    }

    public function test_contact_form_is_rate_limited(): void
    {
        Mail::fake();

        for ($i = 0; $i < 3; $i++) {
            $this->postContact($this->validPayload([
                'email' => "person{$i}@example.com",
            ]))->assertRedirect(route('contact.thanks'));
        }

        $this->postContact($this->validPayload([
            'email' => 'blocked@example.com',
        ]))->assertTooManyRequests();

        $this->assertDatabaseCount(ContactMessage::class, 3);
    }

    private function postContact(array $payload, ?string $from = null): TestResponse
    {
        $request = $this->withSession(['_token' => 'test-token']);

        if ($from !== null) {
            $request = $request->from($from);
        }

        return $request->post(route('contact.store'), array_merge($payload, [
            '_token' => 'test-token',
        ]));
    }

    /**
     * @param  array<string, string>  $overrides
     * @return array<string, string>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jane Artist',
            'email' => 'jane@example.com',
            'subject' => 'Booking question',
            'message' => 'Hello, I would like to learn more about your performances.',
            'website' => '',
            'form_started_at' => (string) now()->subSeconds(10)->timestamp,
            'cf-turnstile-response' => 'test-token',
        ], $overrides);
    }
}
