<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationAdminActivity extends Mailable
{
    use Queueable, SerializesModels;

    /** @param array<string, string> $details */
    public function __construct(
        public string $title,
        public array $details,
        public string $ledgerUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Donation activity: {$this->title}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.donation-admin-activity');
    }
}
