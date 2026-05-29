<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ArtistApplicationRequest;
use App\Mail\ArtistApplicationSubmitted;
use App\Models\ArtistApplication;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class GetInvolvedController extends Controller
{
    public function create()
    {
        return view('pages.get-involved');
    }

    public function store(ArtistApplicationRequest $request)
    {
        $validated = $request->validated();

        $application = ArtistApplication::create(collect($validated)->except(['photo', 'resume'])->all());

        if ($request->hasFile('photo')) {
            $application
                ->addMediaFromRequest('photo')
                ->toMediaCollection('photo');
        }

        if ($request->hasFile('resume')) {
            $application
                ->addMediaFromRequest('resume')
                ->toMediaCollection('resume');
        }

        $this->sendSubmissionNotification($application);

        return redirect()->route('get-involved.thanks');
    }

    public function thanks()
    {
        return view('pages.get-involved-thanks');
    }

    private function sendSubmissionNotification(ArtistApplication $application): void
    {
        try {
            Mail::to(config('mail.from.address'))->send(new ArtistApplicationSubmitted($application));
        } catch (Throwable $exception) {
            Log::warning('Artist application notification failed to send.', [
                'artist_application_id' => $application->id,
                'mail_to' => config('mail.from.address'),
                'exception' => $exception,
            ]);

            try {
                Mail::mailer('log')->to(config('mail.from.address'))->send(new ArtistApplicationSubmitted($application));
            } catch (Throwable $fallbackException) {
                Log::warning('Artist application fallback notification logging failed.', [
                    'artist_application_id' => $application->id,
                    'mail_to' => config('mail.from.address'),
                    'exception' => $fallbackException,
                ]);
            }
        }
    }
}
