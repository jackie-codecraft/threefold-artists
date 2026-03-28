<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\View\View;

class NewsletterPreviewController extends Controller
{
    public function show(Newsletter $newsletter): View
    {
        return view('emails.newsletter', [
            'newsletter' => $newsletter,
            'body' => $newsletter->body,
            'unsubscribeUrl' => '#',
        ]);
    }
}
