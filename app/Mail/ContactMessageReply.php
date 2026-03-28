<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage,
        public string $replyMessage
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: ' . ($this->contactMessage->subject ?: 'Your message to Threefold Artists'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-message-reply',
            with: [
                'contactMessage' => $this->contactMessage,
                'replyMessage' => $this->replyMessage,
            ],
        );
    }
}
