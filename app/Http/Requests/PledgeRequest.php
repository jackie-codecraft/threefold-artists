<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PledgeRequest extends FormRequest
{
    private const MINIMUM_SECONDS_TO_SUBMIT = 3;

    private const MAXIMUM_LINKS = 2;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'message' => ['nullable', 'string', 'max:5000'],
            'pledge_acknowledgment' => ['accepted'],
            'website' => ['nullable', 'prohibited'],
            'form_started_at' => ['required', 'integer'],
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
     * @return array{name: string, email: string, phone?: string|null, amount: mixed, message?: string|null, status: string, acknowledged_at: \Illuminate\Support\Carbon}
     */
    public function pledgeData(): array
    {
        return array_merge($this->safe()->only([
            'name',
            'email',
            'phone',
            'amount',
            'message',
        ]), [
            'status' => 'new',
            'acknowledged_at' => now(),
        ]);
    }

    private function messageLinkCount(): int
    {
        $message = (string) $this->input('message', '');

        return preg_match_all('/https?:\/\/|www\./i', $message);
    }
}
