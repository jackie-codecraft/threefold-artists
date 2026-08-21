<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Services\DonationStatementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationStatementController extends Controller
{
    public function show(Request $request, DonationStatementService $service): View|RedirectResponse
    {
        $donor = $this->authorizedDonor($request);
        if ($donor === null) {
            return redirect()->route('donor-access.request')->with('error', 'Request a secure access link to view statements.');
        }

        $year = (int) $request->integer('year', now()->year);
        abort_unless($year >= 2000 && $year <= now()->year, 404);

        return view('donor-access.statement', [
            'donor' => $donor,
            'statement' => $service->statementFor($donor, $year),
            'organization' => config('donations'),
        ]);
    }

    private function authorizedDonor(Request $request): ?Donor
    {
        $donorId = $request->session()->get('donor_portal.donor_id');

        return is_int($donorId) || ctype_digit((string) $donorId)
            ? Donor::query()->find((int) $donorId)
            : null;
    }
}
