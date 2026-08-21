<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\DonationReceipt;
use App\Models\Donation;
use App\Models\StripeWebhookEvent;
use App\Services\StripeWebhookProcessor;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request, StripeWebhookProcessor $processor): JsonResponse
    {
        $secret = (string) config('services.stripe.webhook_secret');
        if ($secret === '') {
            return response()->json(['message' => 'Webhook endpoint is not configured.'], 503);
        }

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                (string) $request->header('Stripe-Signature'),
                $secret,
            );
        } catch (\UnexpectedValueException|SignatureVerificationException) {
            return response()->json(['message' => 'Invalid Stripe webhook signature.'], 400);
        }

        try {
            $receipts = DB::transaction(function () use ($event, $processor): array {
                $stored = StripeWebhookEvent::query()->create([
                    'stripe_event_id' => $event->id,
                    'event_type' => $event->type,
                    'api_version' => $event->api_version,
                    'livemode' => $event->livemode,
                    'payload' => $event->toArray(),
                    'received_at' => now(),
                ]);

                $receipts = $processor->process($event);
                $stored->update(['processed_at' => now()]);

                return $receipts;
            });
        } catch (QueryException $exception) {
            if (! $this->isDuplicateEvent($exception)) {
                throw $exception;
            }

            // Stripe retries are safe to replay: the processor uses provider IDs
            // as idempotency keys and this also recovers events improved code could
            // not fully normalize when they first arrived.
            $receipts = DB::transaction(fn (): array => $processor->process($event));
        }

        $this->sendReceipts($receipts);

        return response()->json(['received' => true]);
    }

    /** @param list<Donation> $receipts */
    private function sendReceipts(array $receipts): void
    {
        foreach ($receipts as $donation) {
            if ($donation->donor_email === null || $donation->receipt_sent_at !== null) {
                continue;
            }

            Mail::to($donation->donor_email)->send(new DonationReceipt($donation));
            $donation->update(['receipt_sent_at' => now()]);
        }
    }

    private function isDuplicateEvent(QueryException $exception): bool
    {
        return str_contains($exception->getMessage(), 'stripe_webhook_events.stripe_event_id');
    }
}
