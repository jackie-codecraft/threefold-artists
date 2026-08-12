<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartDonationCheckoutRequest extends FormRequest
{
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
            'amount' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'donor_name' => ['nullable', 'string', 'max:255'],
            'donor_email' => ['required', 'email', 'max:255'],
            'donation_type' => ['required', 'in:one-time,monthly,quarterly,annual'],
            'is_anonymous' => ['required', 'boolean'],
        ];
    }

    public function amountInCents(): int
    {
        return (int) round(((float) $this->validated('amount')) * 100);
    }
}
