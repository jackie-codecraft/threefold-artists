<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Donation $donation,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank You for Your Donation - Threefold Artists',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-receipt',
        );
    }
}
