<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public string $replyUrl;

    public function __construct(
        public ContactMessage $contactMessage,
    ) {
        $this->replyUrl = URL::signedRoute('contact-message.reply', ['contactMessage' => $contactMessage->id]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Message: ' . ($this->contactMessage->subject ?? 'General Inquiry'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-message-received',
            with: [
                'replyUrl' => $this->replyUrl,
            ],
        );
    }
}
