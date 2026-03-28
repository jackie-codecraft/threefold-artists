<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\ContactMessageReply;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class ContactMessageReplyController extends Controller
{
    public function show(Request $request, ContactMessage $contactMessage)
    {
        return view('contact.reply', [
            'contactMessage' => $contactMessage,
            'postUrl' => URL::signedRoute('contact-message.reply.send', ['contactMessage' => $contactMessage->id]),
        ]);
    }

    public function send(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'reply_message' => ['required', 'string', 'max:5000'],
        ]);

        Mail::to($contactMessage->email)->send(
            new ContactMessageReply($contactMessage, $validated['reply_message'])
        );

        $contactMessage->update([
            'status' => 'replied',
            'reply' => $validated['reply_message'],
            'replied_at' => now(),
            'is_read' => true,
        ]);

        return redirect(URL::signedRoute('contact-message.reply', ['contactMessage' => $contactMessage->id]))
            ->with('success', true);
    }
}
