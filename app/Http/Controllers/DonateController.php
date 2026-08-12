<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonateController extends Controller
{
    public function show(): View
    {
        $this->ensureDonationsEnabled();

        return view('pages.donate');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $this->ensureDonationsEnabled();

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'donor_name' => ['nullable', 'string', 'max:255'],
            'donor_email' => ['nullable', 'email', 'max:255'],
            'donation_type' => ['nullable', 'in:one-time,monthly'],
            'is_anonymous' => ['nullable'],
        ]);

        $amount = (int) ($request->input('amount') * 100); // cents
        $isRecurring = $request->input('donation_type') === 'monthly';
        $isAnonymous = (bool) $request->input('is_anonymous');

        $stripeKey = config('services.stripe.secret');

        if ($stripeKey === 'sk_test_placeholder' || empty($stripeKey)) {
            abort(503, 'Donations are temporarily unavailable.');
        }

        \Stripe\Stripe::setApiKey($stripeKey);

        if ($isRecurring) {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Monthly Donation to Threefold Artists',
                            'description' => 'Recurring monthly support for performing arts in underserved communities',
                        ],
                        'unit_amount' => $amount,
                        'recurring' => ['interval' => 'month'],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route('donate.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('donate'),
                'customer_email' => $request->input('donor_email'),
                'metadata' => [
                    'donor_name' => $request->input('donor_name'),
                    'is_anonymous' => $isAnonymous ? '1' : '0',
                ],
            ]);
        } else {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Donation to Threefold Artists',
                            'description' => 'Supporting performing arts in underserved communities',
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('donate.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('donate'),
                'customer_email' => $request->input('donor_email'),
                'metadata' => [
                    'donor_name' => $request->input('donor_name'),
                    'is_anonymous' => $isAnonymous ? '1' : '0',
                ],
            ]);
        }

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

    private function ensureDonationsEnabled(): void
    {
        abort_unless(SiteSettings::current()->donationsEnabled(), 404);
    }
}
