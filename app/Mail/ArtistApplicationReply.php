<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\ArtistApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ArtistApplicationReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ArtistApplication $application,
        public string $replyMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: Your artist application to Threefold Artists',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.artist-application-reply',
            with: [
                'application' => $this->application,
                'replyMessage' => $this->replyMessage,
            ],
        );
    }
}
