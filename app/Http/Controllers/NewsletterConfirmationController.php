<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterConfirmationController extends Controller
{
    public function show(Request $request): View
    {
        $subscriber = NewsletterSubscriber::query()
            ->where('token', $request->query('token'))
            ->whereNull('confirmed_at')
            ->first();

        return view('newsletter.confirm', ['subscriber' => $subscriber]);
    }

    public function confirm(Request $request): RedirectResponse
    {
        $validated = $request->validate(['token' => ['required', 'string']]);

        $subscriber = NewsletterSubscriber::query()
            ->where('token', $validated['token'])
            ->whereNull('confirmed_at')
            ->first();

        if ($subscriber) {
            $subscriber->update([
                'confirmed_at' => now(),
                'unsubscribed_at' => null,
            ]);
        }

        return redirect()->route('newsletter.confirmed');
    }

    public function confirmed(): View
    {
        return view('newsletter.confirmed');
    }
}
