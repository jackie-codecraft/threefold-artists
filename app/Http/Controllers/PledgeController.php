<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PledgeRequest;
use App\Mail\PledgeConfirmation;
use App\Mail\PledgeSubmittedNotification;
use App\Models\Pledge;
use App\Models\SiteSettings;
use Illuminate\Support\Facades\Mail;

class PledgeController extends Controller
{
    public function create()
    {
        $this->ensurePledgesEnabled();

        return view('pages.pledge');
    }

    public function store(PledgeRequest $request)
    {
        $this->ensurePledgesEnabled();

        $pledge = Pledge::create($request->pledgeData());
        $adminEmail = $this->pledgeNotificationEmail();

        Mail::to($pledge->email)->send(new PledgeConfirmation($pledge));
        Mail::to($adminEmail)->send(new PledgeSubmittedNotification($pledge));

        return redirect()->route('pledge.thanks');
    }

    public function thanks()
    {
        $this->ensurePledgesEnabled();

        return view('pages.pledge-thanks');
    }

    private function ensurePledgesEnabled(): void
    {
        abort_unless(SiteSettings::current()->pledgesEnabled(), 404);
    }

    private function pledgeNotificationEmail(): string
    {
        $settings = SiteSettings::current();
        $donationsEmail = trim((string) $settings->donations_email);

        if ($donationsEmail !== '') {
            return $donationsEmail;
        }

        return (string) config('mail.from.address');
    }
}
