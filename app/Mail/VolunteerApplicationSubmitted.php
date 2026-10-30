<?php

declare(strict_types=1);

namespace App\Mail;

use App\Filament\Resources\VolunteerApplicationResource;
use App\Models\VolunteerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VolunteerApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public VolunteerApplication $application,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->application->email, $this->application->name)],
            subject: 'New Volunteer Application: '.$this->application->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.volunteer-application-submitted',
            with: [
                'adminUrl' => VolunteerApplicationResource::getUrl('edit', ['record' => $this->application]),
            ],
        );
    }
}
