<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\DonationReceipt;
use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DonateController extends Controller
{
    public function show(): View
    {
        return view('pages.donate');
    }

    public function checkout(Request $request): RedirectResponse
    {
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
            // Stripe not configured yet - simulate success for development
            Donation::create([
                'donor_name' => $request->input('donor_name'),
                'donor_email' => $request->input('donor_email'),
                'amount' => $request->input('amount'),
                'is_recurring' => $isRecurring,
                'recurring_interval' => $isRecurring ? 'monthly' : null,
                'is_anonymous' => $isAnonymous,
                'stripe_payment_id' => 'dev_' . uniqid(),
            ]);

            return redirect()->route('donate.thanks');
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

    public function success(Request $request): RedirectResponse
    {
        $sessionId = $request->get('session_id');

        if ($sessionId) {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            try {
                $session = \Stripe\Checkout\Session::retrieve($sessionId);

                $donation = Donation::create([
                    'donor_name' => $session->metadata->donor_name ?? null,
                    'donor_email' => $session->customer_email,
                    'amount' => $session->mode === 'subscription'
                        ? ($session->amount_total ?? 0) / 100
                        : $session->amount_total / 100,
                    'is_recurring' => $session->mode === 'subscription',
                    'recurring_interval' => $session->mode === 'subscription' ? 'monthly' : null,
                    'is_anonymous' => ($session->metadata->is_anonymous ?? '0') === '1',
                    'stripe_payment_id' => $session->mode === 'subscription' ? null : $session->payment_intent,
                    'stripe_subscription_id' => $session->mode === 'subscription' ? $session->subscription : null,
                ]);

                if ($donation->donor_email) {
                    Mail::to($donation->donor_email)->send(new DonationReceipt($donation));
                    $donation->update(['receipt_sent_at' => now()]);
                }
            } catch (\Exception $e) {
                report($e);
            }
        }

        return redirect()->route('donate.thanks');
    }

    public function thanks(): View
    {
        return view('pages.donate-thanks');
    }
}
