<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\PerformanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PerformanceRequestReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PerformanceRequest $performanceRequest,
        public string $replyMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: Performance request from ' . $this->performanceRequest->organization_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.performance-request-reply',
            with: [
                'performanceRequest' => $this->performanceRequest,
                'replyMessage' => $this->replyMessage,
            ],
        );
    }
}
