<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Donation;
use App\Models\DonationSupport;
use App\Models\Donor;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Stripe\Event;

class StripeWebhookProcessor
{
    /**
     * Process an already-persisted Stripe event. Returns donations whose receipts
     * may be sent only after the enclosing database transaction has committed.
     *
     * @return list<Donation>
     */
    public function process(Event $event): array
    {
        $object = $event->data->object->toArray();

        return match ($event->type) {
            'checkout.session.completed' => $this->checkoutCompleted($object),
            'invoice.paid' => $this->invoicePaid($object),
            'invoice.payment_failed' => $this->invoicePaymentFailed($object),
            'customer.subscription.created', 'customer.subscription.updated', 'customer.subscription.deleted' => $this->subscriptionChanged($object),
            'charge.refunded' => $this->chargeRefunded($object),
            'charge.dispute.closed' => $this->disputeClosed($object),
            default => [],
        };
    }

    /** @return list<Donation> */
    private function checkoutCompleted(array $session): array
    {
        if (($session['mode'] ?? null) !== 'payment' || ($session['payment_status'] ?? null) !== 'paid') {
            return [];
        }

        $donation = Donation::query()->firstOrCreate(
            ['stripe_checkout_session_id' => $session['id']],
            $this->donationAttributes($session, false),
        );

        return $donation->wasRecentlyCreated ? [$donation] : [];
    }

    /** @return list<Donation> */
    private function invoicePaid(array $invoice): array
    {
        $subscriptionId = $this->id($invoice['subscription'] ?? null);
        if ($subscriptionId === null || ($invoice['status'] ?? null) !== 'paid') {
            return [];
        }

        $support = $this->upsertSupportFromInvoice($invoice, $subscriptionId);
        $donation = Donation::query()->firstOrCreate(
            ['stripe_invoice_id' => $invoice['id']],
            [
                ...$this->donationAttributes($invoice, true),
                'donor_id' => $support->donor_id,
                'donation_support_id' => $support->id,
                'stripe_subscription_id' => $subscriptionId,
                'stripe_payment_intent_id' => $this->id($invoice['payment_intent'] ?? null),
                'stripe_charge_id' => $this->id($invoice['charge'] ?? null),
            ],
        );

        return $donation->wasRecentlyCreated ? [$donation] : [];
    }

    /** @return list<Donation> */
    private function invoicePaymentFailed(array $invoice): array
    {
        $subscriptionId = $this->id($invoice['subscription'] ?? null);
        if ($subscriptionId !== null) {
            $this->upsertSupportFromInvoice($invoice, $subscriptionId, 'past_due');
        }

        return [];
    }

    /** @return list<Donation> */
    private function subscriptionChanged(array $subscription): array
    {
        $subscriptionId = $this->id($subscription['id'] ?? null);
        if ($subscriptionId !== null) {
            $this->upsertSupport($subscription, $subscriptionId);
        }

        return [];
    }

    /** @return list<Donation> */
    private function chargeRefunded(array $charge): array
    {
        $chargeId = $this->id($charge['id'] ?? null);
        if ($chargeId === null) {
            return [];
        }

        $paidDonation = Donation::query()
            ->where('stripe_charge_id', $chargeId)
            ->orWhere('stripe_payment_intent_id', $this->id($charge['payment_intent'] ?? null))
            ->first();
        if ($paidDonation === null) {
            return [];
        }

        $receipts = [];
        foreach (Arr::get($charge, 'refunds.data', []) as $refund) {
            $refundId = $this->id($refund['id'] ?? null);
            if ($refundId === null || ($refund['status'] ?? null) !== 'succeeded') {
                continue;
            }

            Donation::query()->firstOrCreate(
                ['stripe_refund_id' => $refundId],
                [
                    'donor_id' => $paidDonation->donor_id,
                    'donation_support_id' => $paidDonation->donation_support_id,
                    'adjustment_for_donation_id' => $paidDonation->id,
                    'donor_name' => $paidDonation->donor_name,
                    'donor_email' => $paidDonation->donor_email,
                    'amount' => -((int) $refund['amount'] / 100),
                    'amount_cents' => -((int) $refund['amount']),
                    'currency' => strtolower((string) ($refund['currency'] ?? $paidDonation->currency ?? 'usd')),
                    'status' => 'refunded',
                    'is_recurring' => $paidDonation->is_recurring,
                    'recurring_interval' => $paidDonation->recurring_interval,
                    'is_anonymous' => $paidDonation->is_anonymous,
                    'public_recognition_consent' => $paidDonation->public_recognition_consent,
                    'stripe_charge_id' => null,
                    'stripe_subscription_id' => $paidDonation->stripe_subscription_id,
                    'refunded_at' => $this->timestamp($refund['created'] ?? null) ?? now(),
                ],
            );
        }

        return $receipts;
    }

    /** @return list<Donation> */
    private function disputeClosed(array $dispute): array
    {
        if (($dispute['status'] ?? null) !== 'lost') {
            return [];
        }

        $disputeId = $this->id($dispute['id'] ?? null);
        $chargeId = $this->id($dispute['charge'] ?? null);
        $amount = (int) ($dispute['amount'] ?? 0);
        if ($disputeId === null || $chargeId === null || $amount <= 0) {
            return [];
        }

        $paidDonation = Donation::query()
            ->where('stripe_charge_id', $chargeId)
            ->where('status', 'paid')
            ->first();
        if ($paidDonation === null) {
            return [];
        }

        Donation::query()->firstOrCreate(
            ['stripe_dispute_id' => $disputeId],
            [
                'donor_id' => $paidDonation->donor_id,
                'donation_support_id' => $paidDonation->donation_support_id,
                'adjustment_for_donation_id' => $paidDonation->id,
                'donor_name' => $paidDonation->donor_name,
                'donor_email' => $paidDonation->donor_email,
                'amount' => -($amount / 100),
                'amount_cents' => -$amount,
                'currency' => strtolower((string) ($dispute['currency'] ?? $paidDonation->currency ?? 'usd')),
                'status' => 'disputed',
                'is_recurring' => $paidDonation->is_recurring,
                'recurring_interval' => $paidDonation->recurring_interval,
                'is_anonymous' => $paidDonation->is_anonymous,
                'public_recognition_consent' => $paidDonation->public_recognition_consent,
                'stripe_subscription_id' => $paidDonation->stripe_subscription_id,
                'disputed_at' => $this->timestamp($dispute['created'] ?? null) ?? now(),
            ],
        );

        return [];
    }

    private function upsertSupportFromInvoice(array $invoice, string $subscriptionId, ?string $fallbackStatus = null): DonationSupport
    {
        $existing = DonationSupport::query()->where('stripe_subscription_id', $subscriptionId)->first();
        if ($existing !== null) {
            if ($fallbackStatus !== null) {
                $existing->update(['status' => $fallbackStatus]);
            }

            return $existing;
        }

        return $this->upsertSupport([
            'id' => $subscriptionId,
            'customer' => $invoice['customer'] ?? null,
            'status' => $fallbackStatus ?? 'active',
            'items' => ['data' => $invoice['lines']['data'] ?? []],
            'pause_collection' => null,
            'cancel_at_period_end' => false,
            'current_period_start' => $invoice['period_start'] ?? null,
            'current_period_end' => $invoice['period_end'] ?? null,
            'customer_email' => $invoice['customer_email'] ?? null,
            'customer_name' => $invoice['customer_name'] ?? null,
            'amount' => $invoice['amount_paid'] ?? 0,
            'currency' => $invoice['currency'] ?? 'usd',
        ], $subscriptionId);
    }

    private function upsertSupport(array $subscription, string $subscriptionId): DonationSupport
    {
        $customerId = $this->id($subscription['customer'] ?? null);
        $email = $subscription['customer_email'] ?? null;
        $name = $subscription['customer_name'] ?? null;
        $donor = $this->donor($customerId, $email, $name);
        $item = Arr::first(Arr::get($subscription, 'items.data', []), fn (array $item): bool => true) ?? [];
        $price = $item['price'] ?? [];
        $pause = $subscription['pause_collection'] ?? null;

        return DonationSupport::query()->updateOrCreate(
            ['stripe_subscription_id' => $subscriptionId],
            [
                'donor_id' => $donor->id,
                'stripe_customer_id' => $customerId,
                'stripe_price_id' => $this->id($price['id'] ?? null),
                'amount_cents' => (int) ($price['unit_amount'] ?? $subscription['amount'] ?? 0),
                'currency' => strtolower((string) ($price['currency'] ?? $subscription['currency'] ?? 'usd')),
                'interval' => $this->canonicalInterval($price['recurring']['interval'] ?? null, $price['recurring']['interval_count'] ?? 1),
                'interval_count' => max(1, (int) ($price['recurring']['interval_count'] ?? 1)),
                'status' => (string) ($subscription['status'] ?? 'active'),
                'pause_collection_behavior' => is_array($pause) ? ($pause['behavior'] ?? null) : null,
                'paused_at' => is_array($pause) ? now() : null,
                'pause_resumes_at' => $this->timestamp(is_array($pause) ? ($pause['resumes_at'] ?? null) : null),
                'paused_until' => $this->timestamp(is_array($pause) ? ($pause['resumes_at'] ?? null) : null),
                'cancel_at_period_end' => (bool) ($subscription['cancel_at_period_end'] ?? false),
                'current_period_starts_at' => $this->timestamp($subscription['current_period_start'] ?? null),
                'current_period_ends_at' => $this->timestamp($subscription['current_period_end'] ?? null),
            ],
        );
    }

    private function donor(?string $customerId, ?string $email, ?string $name): Donor
    {
        $email ??= $customerId !== null ? "{$customerId}@stripe.invalid" : 'unknown@stripe.invalid';

        return Donor::query()->updateOrCreate(
            ['email' => $email],
            array_filter(['name' => $name, 'stripe_customer_id' => $customerId], fn ($value): bool => $value !== null),
        );
    }

    private function donationAttributes(array $object, bool $recurring): array
    {
        $metadata = is_array($object['metadata'] ?? null)
            ? $object['metadata']
            : (is_array(Arr::get($object, 'subscription_details.metadata')) ? Arr::get($object, 'subscription_details.metadata') : []);
        $email = $object['customer_email'] ?? $object['customer_details']['email'] ?? null;
        $name = $metadata['donor_name'] ?? $object['customer_details']['name'] ?? $object['customer_name'] ?? null;
        $customerId = $this->id($object['customer'] ?? null);
        $donor = $email !== null ? $this->donor($customerId, $email, $name) : null;
        $publicRecognitionConsent = $this->publicRecognitionConsent($metadata);

        if ($donor !== null && $publicRecognitionConsent !== null) {
            $donor->update(['public_recognition_consent' => $publicRecognitionConsent]);
        }
        $amount = (int) ($object['amount_total'] ?? $object['amount_paid'] ?? 0);

        return [
            'donor_id' => $donor?->id,
            'donor_name' => $name,
            'donor_email' => $email,
            'amount' => $amount / 100,
            'amount_cents' => $amount,
            'currency' => strtolower((string) ($object['currency'] ?? 'usd')),
            'status' => 'paid',
            'is_recurring' => $recurring,
            'recurring_interval' => $recurring ? $this->canonicalInterval(
                Arr::get($object, 'lines.data.0.price.recurring.interval'),
                Arr::get($object, 'lines.data.0.price.recurring.interval_count', 1),
            ) : null,
            'is_anonymous' => ($metadata['is_anonymous'] ?? '0') === '1',
            'public_recognition_consent' => $publicRecognitionConsent,
            'stripe_payment_id' => $this->id($object['payment_intent'] ?? null),
            'stripe_payment_intent_id' => $this->id($object['payment_intent'] ?? null),
            'stripe_charge_id' => $this->id($object['charge'] ?? null),
            'paid_at' => now(),
        ];
    }

    private function canonicalInterval(mixed $interval, mixed $intervalCount): ?string
    {
        $count = max(1, (int) $intervalCount);

        if ($interval === 'month' && $count === 1) {
            return 'monthly';
        }

        if ($interval === 'month' && $count === 3) {
            return 'quarterly';
        }

        return $interval === 'year' && $count === 1 ? 'annual' : null;
    }

    /** @param array<string, mixed> $metadata */
    private function publicRecognitionConsent(array $metadata): ?bool
    {
        return array_key_exists('public_recognition_consent', $metadata)
            ? (string) $metadata['public_recognition_consent'] === '1'
            : null;
    }

    private function id(mixed $value): ?string
    {
        return is_array($value) ? ($value['id'] ?? null) : (is_string($value) ? $value : null);
    }

    private function timestamp(mixed $value): ?Carbon
    {
        return is_numeric($value) ? Carbon::createFromTimestamp((int) $value) : null;
    }
}
