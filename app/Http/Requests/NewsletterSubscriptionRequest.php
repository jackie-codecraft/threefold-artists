<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\ValidTurnstile;
use Illuminate\Foundation\Http\FormRequest;

class NewsletterSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:50'],
            'cf-turnstile-response' => ['required', new ValidTurnstile($this->ip())],
        ];
    }
}
