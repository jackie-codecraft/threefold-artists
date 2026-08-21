<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ChangeDonationSupportAmountRequest;
use App\Http\Requests\PauseDonationSupportRequest;
use App\Models\DonationSupport;
use App\Models\Donor;
use App\Services\DonationSupportService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DonationSupportController extends Controller
{
    public function pause(PauseDonationSupportRequest $request, DonationSupport $support, DonationSupportService $service): RedirectResponse
    {
        $donor = $this->authorizedDonor($request);
        if ($donor === null) {
            return redirect()->route('donor-access.request')->with('error', 'Request a secure access link to manage recurring support.');
        }

        try {
            $service->requestPause($donor, $support, (int) $request->validated('periods'));
        } catch (AuthorizationException) {
            abort(404);
        }

        return redirect()->route('donor-portal')->with('success', 'Your pause request was sent. Your support will update after Stripe confirms it.');
    }

    public function changeAmount(ChangeDonationSupportAmountRequest $request, DonationSupport $support, DonationSupportService $service): RedirectResponse
    {
        $donor = $this->authorizedDonor($request);
        if ($donor === null) {
            return redirect()->route('donor-access.request')->with('error', 'Request a secure access link to manage recurring support.');
        }

        try {
            $service->requestAmountChange($donor, $support, $request->amountInCents(), $request->validated('cadence'));
        } catch (AuthorizationException) {
            abort(404);
        }

        return redirect()->route('donor-portal')->with('success', 'Your new recurring amount is scheduled for your next renewal. Your current billing period and amount are unchanged.');
    }

    private function authorizedDonor(Request $request): ?Donor
    {
        $donorId = $request->session()->get('donor_portal.donor_id');

        return is_int($donorId) || ctype_digit((string) $donorId)
            ? Donor::query()->find((int) $donorId)
            : null;
    }
}
