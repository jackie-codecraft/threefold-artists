<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\ValidTurnstile;
use App\Support\DisciplineOptions;
use Illuminate\Foundation\Http\FormRequest;

class ArtistApplicationRequest extends FormRequest
{
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
            'discipline' => ['required', 'string', 'in:'.implode(',', DisciplineOptions::values())],
            'experience' => ['nullable', 'string', 'max:2000'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'availability' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'supporting_media' => ['nullable', 'array', 'max:5'],
            'supporting_media.*' => ['file', 'mimes:mp4,mov,webm,mp3,wav,m4a,jpg,jpeg,png,webp,pdf', 'max:20480'],
            'cf-turnstile-response' => ['required', new ValidTurnstile($this->ip())],
        ];
    }
}
