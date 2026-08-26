<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\ValidTurnstile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ContactMessageRequest extends FormRequest
{
    private const MINIMUM_SECONDS_TO_SUBMIT = 3;

    private const MAXIMUM_LINKS = 2;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'website' => ['nullable', 'prohibited'],
            'form_started_at' => ['required', 'integer'],
            'cf-turnstile-response' => ['required', new ValidTurnstile($this->ip())],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $startedAt = (int) $this->input('form_started_at', 0);

                if ($startedAt > 0 && now()->timestamp - $startedAt < self::MINIMUM_SECONDS_TO_SUBMIT) {
                    $validator->errors()->add('form_started_at', 'Please take a moment before submitting the form.');
                }

                if ($this->messageLinkCount() > self::MAXIMUM_LINKS) {
                    $validator->errors()->add('message', 'Please limit links in your message.');
                }
            },
        ];
    }

    /**
     * @return array{name: string, email: string, subject?: string|null, message: string}
     */
    public function contactData(): array
    {
        return $this->safe()->only([
            'name',
            'email',
            'subject',
            'message',
        ]);
    }

    private function messageLinkCount(): int
    {
        $message = (string) $this->input('message', '');

        return preg_match_all('/https?:\/\/|www\./i', $message);
    }
}
