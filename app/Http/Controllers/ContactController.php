<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function create()
    {
        return view('pages.contact');
    }

    public function store(ContactMessageRequest $request)
    {
        $message = ContactMessage::create($request->validated());

        Mail::to(config('mail.from.address'))->send(
            new ContactMessageReceived($message)
        );

        return redirect()->route('contact.thanks');
    }

    public function thanks()
    {
        return view('pages.contact-thanks');
    }
}
