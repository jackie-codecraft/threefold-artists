<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\ValidTurnstile;
use App\Support\DisciplineOptions;
use App\Support\UploadLimits;
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
            'demo_video_url' => ['nullable', 'url:http,https', 'max:2048'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.UploadLimits::PHOTO_KB],
            'resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:'.UploadLimits::RESUME_KB],
            'supporting_media' => ['nullable', 'array', 'max:'.UploadLimits::SUPPORTING_MEDIA_MAX_FILES],
            'supporting_media.*' => ['file', 'mimes:mp4,mov,webm,mp3,wav,m4a,jpg,jpeg,png,webp,pdf', 'max:'.UploadLimits::SUPPORTING_MEDIA_KB],
            'cf-turnstile-response' => ['required', new ValidTurnstile($this->ip())],
        ];
    }
}
