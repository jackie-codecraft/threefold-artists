<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Rules\ValidTurnstile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class TurnstileValidationTest extends TestCase
{
    public function test_it_accepts_a_token_verified_by_turnstile(): void
    {
        config()->set('services.turnstile.enabled', true);
        config()->set('services.turnstile.secret_key', 'test-secret');
        Http::fake([
            'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
                'success' => true,
            ]),
        ]);

        $validator = Validator::make([
            'cf-turnstile-response' => 'verified-token',
        ], [
            'cf-turnstile-response' => ['required', new ValidTurnstile('127.0.0.1')],
        ]);

        $this->assertTrue($validator->passes());
        Http::assertSent(fn ($request): bool => $request->url() === 'https://challenges.cloudflare.com/turnstile/v0/siteverify'
            && $request['secret'] === 'test-secret'
            && $request['response'] === 'verified-token'
            && $request['remoteip'] === '127.0.0.1');
    }

    public function test_it_rejects_missing_or_invalid_turnstile_tokens(): void
    {
        config()->set('services.turnstile.enabled', true);
        config()->set('services.turnstile.secret_key', 'test-secret');
        Http::fake([
            'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
                'success' => false,
            ]),
        ]);

        $validator = Validator::make([
            'cf-turnstile-response' => 'invalid-token',
        ], [
            'cf-turnstile-response' => ['required', new ValidTurnstile('127.0.0.1')],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('cf-turnstile-response'));
    }
}
