<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnsubscribeController extends Controller
{
    public function confirm(Request $request): View
    {
        $subscriber = NewsletterSubscriber::where('token', $request->query('token'))->first();

        if (! $subscriber) {
            return view('unsubscribe.done', ['found' => false]);
        }

        return view('unsubscribe.confirm', ['subscriber' => $subscriber]);
    }

    public function process(Request $request): View
    {
        $request->validate(['token' => 'required|string']);

        $subscriber = NewsletterSubscriber::where('token', $request->input('token'))->first();

        if ($subscriber) {
            $subscriber->update(['unsubscribed_at' => now()]);
        }

        return view('unsubscribe.done', ['found' => (bool) $subscriber]);
    }
}
