<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\PerformanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PerformanceRequestSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PerformanceRequest $performanceRequest,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Performance Request: ' . $this->performanceRequest->organization_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.performance-request-submitted',
        );
    }
}
