<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\VolunteerApplicationRequest;
use App\Mail\VolunteerApplicationSubmitted;
use App\Mail\VolunteerApplicationThankYou;
use App\Models\SiteSettings;
use App\Models\VolunteerApplication;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class GetInvolvedController extends Controller
{
    public function create()
    {
        return view('pages.get-involved', [
            'interests' => VolunteerApplication::INTERESTS,
            'availabilityOptions' => VolunteerApplication::AVAILABILITY,
            'privacyEmail' => SiteSettings::current()->contactEmail(),
        ]);
    }

    public function store(VolunteerApplicationRequest $request)
    {
        $application = VolunteerApplication::create($request->applicationData());

        $this->sendQuietly($application, SiteSettings::current()->adminNotificationEmail(), new VolunteerApplicationSubmitted($application), 'admin notification');
        $this->sendQuietly($application, $application->email, new VolunteerApplicationThankYou($application), 'thank-you email');

        return redirect()->route('get-involved.thanks');
    }

    public function thanks()
    {
        return view('pages.get-involved-thanks');
    }

    /**
     * A saved submission must never be lost to a mail outage, so failures are logged, not thrown.
     */
    private function sendQuietly(VolunteerApplication $application, string $recipient, Mailable $mailable, string $description): void
    {
        try {
            Mail::to($recipient)->send($mailable);
        } catch (Throwable $exception) {
            Log::warning("Volunteer application {$description} failed to send.", [
                'volunteer_application_id' => $application->id,
                'exception' => $exception,
            ]);
        }
    }
}
