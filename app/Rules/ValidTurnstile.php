<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Throwable;

class ValidTurnstile implements ValidationRule
{
    public function __construct(private readonly ?string $ipAddress = null) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! config('services.turnstile.enabled')) {
            return;
        }

        $secretKey = config('services.turnstile.secret_key');

        if (blank($secretKey)) {
            $fail('We could not verify that you are human. Please try again.');

            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secretKey,
                    'response' => $value,
                    'remoteip' => $this->ipAddress,
                ]);
        } catch (Throwable) {
            $fail('We could not verify that you are human. Please try again.');

            return;
        }

        if (! $response->json('success')) {
            $fail('We could not verify that you are human. Please try again.');
        }
    }
}
