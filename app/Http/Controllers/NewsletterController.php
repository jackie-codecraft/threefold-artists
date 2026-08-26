<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscriptionRequest;
use App\Mail\NewsletterSubscriptionConfirmation;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function store(NewsletterSubscriptionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $subscriber = NewsletterSubscriber::firstOrNew(['email' => $validated['email']]);

        if ($subscriber->exists && $subscriber->confirmed_at && $subscriber->unsubscribed_at === null) {
            return back()->with('newsletter_success', 'You are already subscribed.');
        }

        $subscriber->fill([
            'name' => $validated['name'] ?? null,
            'source' => $validated['source'] ?? 'website',
            'confirmed_at' => null,
            'token' => Str::random(48),
        ]);
        $subscriber->save();

        Mail::to($subscriber->email)->send(new NewsletterSubscriptionConfirmation($subscriber));

        return back()->with('newsletter_success', 'Check your email to confirm your subscription.');
    }
}
