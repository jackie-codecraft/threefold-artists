<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StartDonationCheckoutRequest;
use App\Models\DonationSupport;
use App\Models\Donor;
use App\Models\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Stripe\StripeClient;

class DonateController extends Controller
{
    public function show(): View
    {
        $this->ensureDonationsEnabled();

        return view('pages.donate');
    }

    public function checkout(StartDonationCheckoutRequest $request): RedirectResponse
    {
        $this->ensureDonationsEnabled();

        $stripeSecret = (string) config('services.stripe.secret');

        if ($stripeSecret === '' || $stripeSecret === 'sk_test_placeholder') {
            abort(503, 'Donations are temporarily unavailable.');
        }

        $donationType = $request->validated('donation_type');
        $isRecurring = $donationType !== 'one-time';
        if ($isRecurring && $this->hasActiveRecurringSupport($request->validated('donor_email'))) {
            throw ValidationException::withMessages([
                'donor_email' => 'A recurring donation already exists for this email. Use Manage My Donations to update its amount or cadence.',
            ]);
        }
        $interval = match ($donationType) {
            'monthly' => ['interval' => 'month'],
            'quarterly' => ['interval' => 'month', 'interval_count' => 3],
            'annual' => ['interval' => 'year'],
            default => null,
        };
        $amount = $request->amountInCents();
        $donorName = $request->validated('donor_name');
        $isAnonymous = $request->boolean('is_anonymous');
        $publicRecognitionConsent = $request->boolean('public_recognition_consent');

        $priceData = [
            'currency' => 'usd',
            'product_data' => [
                'name' => $isRecurring
                    ? ucfirst($donationType).' Donation to Threefold Artists'
                    : 'Donation to Threefold Artists',
            ],
            'unit_amount' => $amount,
        ];

        if ($interval !== null) {
            $priceData['recurring'] = $interval;
        }

        $metadata = array_filter([
            'donor_name' => $donorName,
            'is_anonymous' => $isAnonymous ? '1' : '0',
            'public_recognition_consent' => $publicRecognitionConsent ? '1' : '0',
        ], static fn (?string $value): bool => $value !== null);

        $session = (new StripeClient($stripeSecret))->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => $priceData,
                'quantity' => 1,
            ]],
            'mode' => $isRecurring ? 'subscription' : 'payment',
            'success_url' => route('donate.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('donate'),
            'customer_email' => $request->validated('donor_email'),
            'metadata' => $metadata,
            ...($isRecurring ? ['subscription_data' => ['metadata' => $metadata]] : []),
        ]);

        return redirect($session->url);
    }

    public function success(): RedirectResponse
    {
        $this->ensureDonationsEnabled();

        return redirect()->route('donate.thanks');
    }

    public function thanks(): View
    {
        $this->ensureDonationsEnabled();

        return view('pages.donate-thanks');
    }

    private function hasActiveRecurringSupport(string $email): bool
    {
        $donor = Donor::query()->where('email', mb_strtolower(trim($email)))->first();

        return $donor !== null && DonationSupport::query()
            ->where('donor_id', $donor->id)
            ->whereIn('status', ['active', 'trialing', 'past_due', 'incomplete'])
            ->where('cancel_at_period_end', false)
            ->exists();
    }

    private function ensureDonationsEnabled(): void
    {
        abort_unless(SiteSettings::current()->donationsEnabled(), 404);
    }
}
