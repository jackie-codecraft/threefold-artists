<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RequestDonorAccessLinkRequest;
use App\Mail\DonorAccessLinkMail;
use App\Models\Donor;
use App\Models\DonorAccessLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Stripe\StripeClient;

class DonorPortalController extends Controller
{
    private const SESSION_DONOR_ID = 'donor_portal.donor_id';

    public function requestForm(): View
    {
        return view('donor-access.request');
    }

    public function sendAccessLink(RequestDonorAccessLinkRequest $request): RedirectResponse
    {
        $email = $request->normalizedEmail();
        $donor = Donor::query()->where('email', $email)->first();

        if ($donor !== null) {
            $token = random_bytes(64);

            DonorAccessLink::query()->create([
                'donor_id' => $donor->id,
                'email' => $donor->email,
                'token_hash' => hash('sha256', $token),
                'purpose' => 'portal',
                'expires_at' => now()->addMinutes(30),
            ]);

            Mail::to($donor->email)->send(new DonorAccessLinkMail(
                route('donor-access.consume', ['token' => Str::of(base64_encode($token))->replace(['+', '/', '='], ['-', '_', ''])->toString()]),
            ));
        }

        return redirect()->route('donor-access.request')->with(
            'success',
            'If an eligible donation record matches that email address, we have sent a secure access link.'
        );
    }

    public function consume(Request $request, string $token): RedirectResponse
    {
        $base64Token = strtr($token, '-_', '+/');
        $rawToken = base64_decode($base64Token.str_repeat('=', (4 - strlen($base64Token) % 4) % 4), true);

        if ($rawToken === false || strlen($rawToken) !== 64) {
            return redirect()->route('donor-access.request')->with('error', 'This access link is invalid or has expired.');
        }

        $tokenHash = hash('sha256', $rawToken);
        $used = DonorAccessLink::query()
            ->where('token_hash', $tokenHash)
            ->where('purpose', 'portal')
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->update(['used_at' => now()]);

        if ($used !== 1) {
            return redirect()->route('donor-access.request')->with('error', 'This access link is invalid or has expired.');
        }

        $link = DonorAccessLink::query()->where('token_hash', $tokenHash)->firstOrFail();

        if ($link->donor_id === null) {
            return redirect()->route('donor-access.request')->with('error', 'This access link is invalid or has expired.');
        }

        $request->session()->regenerate();
        $request->session()->put(self::SESSION_DONOR_ID, $link->donor_id);

        return redirect()->route('donor-portal');
    }

    public function show(Request $request): View|RedirectResponse
    {
        $donor = $this->authorizedDonor($request);

        if ($donor === null) {
            return redirect()->route('donor-access.request')->with('error', 'Request a secure access link to view donation history.');
        }

        return view('donor-access.portal', [
            'donor' => $donor,
            'donations' => $donor->donations()->latest('paid_at')->latest('id')->get(),
            'supports' => $donor->donationSupports()->latest('id')->get(),
        ]);
    }

    public function billingPortal(Request $request): RedirectResponse
    {
        $donor = $this->authorizedDonor($request);
        $stripeSecret = (string) config('services.stripe.secret');

        if ($donor === null || blank($donor->stripe_customer_id) || $stripeSecret === '' || $stripeSecret === 'sk_test_placeholder') {
            return redirect()->route('donor-portal')->with('error', 'Online donation management is temporarily unavailable.');
        }

        $session = (new StripeClient($stripeSecret))->billingPortal->sessions->create([
            'customer' => $donor->stripe_customer_id,
            'return_url' => route('donor-portal'),
        ]);

        return redirect($session->url);
    }

    private function authorizedDonor(Request $request): ?Donor
    {
        $donorId = $request->session()->get(self::SESSION_DONOR_ID);

        return is_int($donorId) || ctype_digit((string) $donorId)
            ? Donor::query()->find((int) $donorId)
            : null;
    }
}
