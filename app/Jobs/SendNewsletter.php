<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewsletter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 600;

    public function __construct(
        public Newsletter $newsletter
    ) {}

    public function handle(): void
    {
        $this->newsletter->update(['status' => 'sending']);

        $query = NewsletterSubscriber::active()->confirmed();

        if ($this->newsletter->recipient_type === 'custom' && ! empty($this->newsletter->recipient_ids)) {
            $query->whereIn('id', $this->newsletter->recipient_ids);
        }

        $sent = 0;

        $query->chunk(100, function ($subscribers) use (&$sent): void {
            foreach ($subscribers as $subscriber) {
                try {
                    Mail::to($subscriber->email)->send(
                        new NewsletterMail($this->newsletter, $subscriber)
                    );
                    $sent++;
                } catch (\Throwable $e) {
                    Log::warning("Failed to send newsletter to {$subscriber->email}: {$e->getMessage()}");
                }
            }
        });

        $this->newsletter->update([
            'status' => 'sent',
            'sent_at' => now(),
            'total_sent' => $sent,
        ]);
    }
}
