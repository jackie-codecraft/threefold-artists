<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage,
    ) {}

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
        );
    }
}
