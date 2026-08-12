<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonorAccessLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $accessUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Access your Threefold Artists donation history');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.donor-access-link');
    }
}
