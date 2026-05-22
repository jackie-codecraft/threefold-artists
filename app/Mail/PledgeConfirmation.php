<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Pledge;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PledgeConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pledge $pledge,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank You for Your Founding Supporter Pledge - Threefold Artists',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pledge-confirmation',
        );
    }
}
