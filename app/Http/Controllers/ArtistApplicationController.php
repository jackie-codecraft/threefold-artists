<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ArtistApplicationRequest;
use App\Mail\ArtistApplicationSubmitted;
use App\Models\ArtistApplication;
use App\Models\SiteSettings;
use App\Support\UploadLimits;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ArtistApplicationController extends Controller
{
    public function create()
    {
        return view('pages.artist-application', [
            'uploadLimits' => UploadLimits::forBrowser(),
        ]);
    }

    public function store(ArtistApplicationRequest $request)
    {
        $validated = $request->validated();

        $application = ArtistApplication::create(collect($validated)->except(['photo', 'resume', 'supporting_media'])->all());

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

        foreach ($request->file('supporting_media', []) as $media) {
            $application
                ->addMedia($media)
                ->toMediaCollection('supporting_media');
        }

        $this->sendSubmissionNotification($application);

        return redirect()->route('artist-application.thanks');
    }

    public function thanks()
    {
        return view('pages.artist-application-thanks');
    }

    private function sendSubmissionNotification(ArtistApplication $application): void
    {
        $recipient = SiteSettings::current()->adminNotificationEmail();

        try {
            Mail::to($recipient)->send(new ArtistApplicationSubmitted($application));
        } catch (Throwable $exception) {
            Log::warning('Artist application notification failed to send.', [
                'artist_application_id' => $application->id,
                'mail_to' => $recipient,
                'exception' => $exception,
            ]);

            try {
                Mail::mailer('log')->to($recipient)->send(new ArtistApplicationSubmitted($application));
            } catch (Throwable $fallbackException) {
                Log::warning('Artist application fallback notification logging failed.', [
                    'artist_application_id' => $application->id,
                    'mail_to' => $recipient,
                    'exception' => $fallbackException,
                ]);
            }
        }
    }
}
