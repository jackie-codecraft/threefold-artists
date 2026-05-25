<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Pledge;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PledgeSubmittedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pledge $pledge,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->pledge->email, $this->pledge->name)],
            subject: 'New Founding Supporter Pledge: '.$this->pledge->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pledge-submitted-notification',
        );
    }
}
