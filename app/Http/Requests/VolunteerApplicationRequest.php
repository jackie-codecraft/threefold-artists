<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\VolunteerApplication;
use App\Rules\ValidTurnstile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class VolunteerApplicationRequest extends FormRequest
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
            'phone' => ['nullable', 'string', 'max:50'],
            'interests' => ['required', 'array', 'min:1'],
            'interests.*' => ['string', 'distinct', Rule::in(array_keys(VolunteerApplication::INTERESTS))],
            'availability' => ['nullable', 'array'],
            'availability.*' => ['string', 'distinct', Rule::in(array_keys(VolunteerApplication::AVAILABILITY))],
            'experience' => ['nullable', 'string', 'max:2000'],
            'message' => ['nullable', 'string', 'max:2000'],
            'contact_consent' => ['accepted'],
            'website' => ['nullable', 'prohibited'],
            'form_started_at' => ['required', 'integer'],
            'cf-turnstile-response' => ['required', new ValidTurnstile($this->ip())],
        ];
    }

    public function messages(): array
    {
        return [
            'interests.required' => 'Please choose at least one way you would like to help.',
            'interests.min' => 'Please choose at least one way you would like to help.',
            'contact_consent.accepted' => 'Please agree to be contacted so we can follow up about volunteering.',
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

                foreach (['experience', 'message'] as $field) {
                    if (preg_match_all('/https?:\/\/|www\./i', (string) $this->input($field, '')) > self::MAXIMUM_LINKS) {
                        $validator->errors()->add($field, 'Please limit links in your answer.');
                    }
                }
            },
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function applicationData(): array
    {
        $data = $this->safe()->only(['name', 'email', 'phone', 'interests', 'availability', 'experience', 'message']);

        $data['interests'] = array_values($data['interests']);
        $data['availability'] = array_values($data['availability'] ?? []);
        $data['contact_consent_at'] = now();

        return $data;
    }
}
