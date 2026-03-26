<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\DonationReceipt;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DonateController extends Controller
{
    public function show()
    {
        return view('pages.donate');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'donor_name' => ['nullable', 'string', 'max:255'],
            'donor_email' => ['nullable', 'email', 'max:255'],
        ]);

        $amount = (int) ($request->input('amount') * 100); // cents

        $stripeKey = config('services.stripe.secret');

        if ($stripeKey === 'sk_test_placeholder' || empty($stripeKey)) {
            // Stripe not configured yet - simulate success for development
            $donation = Donation::create([
                'donor_name' => $request->input('donor_name'),
                'donor_email' => $request->input('donor_email'),
                'amount' => $request->input('amount'),
                'stripe_payment_id' => 'dev_' . uniqid(),
            ]);

            return redirect()->route('donate.thanks');
        }

        \Stripe\Stripe::setApiKey($stripeKey);

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
            ],
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if ($sessionId) {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            try {
                $session = \Stripe\Checkout\Session::retrieve($sessionId);

                $donation = Donation::create([
                    'donor_name' => $session->metadata->donor_name ?? null,
                    'donor_email' => $session->customer_email,
                    'amount' => $session->amount_total / 100,
                    'stripe_payment_id' => $session->payment_intent,
                ]);

                if ($donation->donor_email) {
                    Mail::to($donation->donor_email)->send(new DonationReceipt($donation));
                    $donation->update(['receipt_sent_at' => now()]);
                }
            } catch (\Exception $e) {
                // Log error but still show thanks page
                report($e);
            }
        }

        return redirect()->route('donate.thanks');
    }

    public function thanks()
    {
        return view('pages.donate-thanks');
    }
}
