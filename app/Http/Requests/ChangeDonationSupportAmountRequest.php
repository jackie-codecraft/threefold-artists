<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeDonationSupportAmountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1', 'max:100000'],
        ];
    }

    public function amountInCents(): int
    {
        return (int) round(((float) $this->validated('amount')) * 100);
    }
}
