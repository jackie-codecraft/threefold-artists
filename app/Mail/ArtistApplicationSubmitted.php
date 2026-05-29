<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\ArtistApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ArtistApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public string $replyUrl;

    public function __construct(
        public ArtistApplication $application,
    ) {
        $this->replyUrl = URL::signedRoute('artist-application.reply', ['artistApplication' => $application->id]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Artist Application: '.$this->application->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.artist-application-submitted',
            with: [
                'replyUrl' => $this->replyUrl,
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return collect(['photo', 'resume'])
            ->map(fn (string $collection) => $this->application->getFirstMedia($collection))
            ->filter()
            ->map(fn ($media): Attachment => Attachment::fromStorageDisk($media->disk, $media->getPathRelativeToRoot())
                ->as($media->file_name)
                ->withMime($media->mime_type))
            ->values()
            ->all();
    }
}
