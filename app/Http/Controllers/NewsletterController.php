<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:50'],
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => $request->input('email')],
            [
                'name' => $request->input('name'),
                'source' => $request->input('source', 'website'),
                'confirmed_at' => now(),
                'unsubscribed_at' => null,
            ]
        );

        return back()->with('newsletter_success', 'Thank you for subscribing!');
    }
}
