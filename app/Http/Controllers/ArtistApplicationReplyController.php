<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\ArtistApplicationReply;
use App\Models\ArtistApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class ArtistApplicationReplyController extends Controller
{
    public function show(Request $request, ArtistApplication $artistApplication): View
    {
        return view('artist-application.reply', [
            'application' => $artistApplication,
            'postUrl' => URL::signedRoute('artist-application.reply.send', ['artistApplication' => $artistApplication->id]),
        ]);
    }

    public function send(Request $request, ArtistApplication $artistApplication): RedirectResponse
    {
        $validated = $request->validate([
            'reply_message' => ['required', 'string', 'max:5000'],
        ]);

        Mail::to($artistApplication->email)->send(
            new ArtistApplicationReply($artistApplication, $validated['reply_message'])
        );

        $artistApplication->update([
            'status' => 'replied',
            'reply' => $validated['reply_message'],
            'replied_at' => now(),
        ]);

        return redirect(URL::signedRoute('artist-application.reply', ['artistApplication' => $artistApplication->id]))
            ->with('success', true);
    }
}
