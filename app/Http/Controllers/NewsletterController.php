<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscriptionRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    public function store(NewsletterSubscriptionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        NewsletterSubscriber::updateOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'] ?? null,
                'source' => $validated['source'] ?? 'website',
                'confirmed_at' => now(),
                'unsubscribed_at' => null,
            ]
        );

        return back()->with('newsletter_success', 'Thank you for subscribing!');
    }
}
