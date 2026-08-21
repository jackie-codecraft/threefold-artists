<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\DonationAdminActivity;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Stripe\Event;

class DonationAdminActivityNotifier
{
    public function notify(Event $event): void
    {
        $activity = $this->activityFor($event);
        if ($activity === null) {
            return;
        }

        User::query()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('id')
            ->each(function (User $admin) use ($activity): void {
                Mail::to($admin->email)->send(new DonationAdminActivity(
                    title: $activity['title'],
                    details: $activity['details'],
                    ledgerUrl: route('filament.admin.resources.donations.index'),
                ));
            });
    }

    /** @return array{title: string, details: array<string, string>}|null */
    private function activityFor(Event $event): ?array
    {
        $object = $event->data->object->toArray();
        $details = $this->details($object);

        return match ($event->type) {
            'checkout.session.completed' => ($object['mode'] ?? null) === 'payment' && ($object['payment_status'] ?? null) === 'paid'
                ? ['title' => 'New one-time donation received', 'details' => $details]
                : null,
            'invoice.paid' => ['title' => 'Recurring donation received', 'details' => $details],
            'invoice.payment_failed' => ['title' => 'Recurring payment failed', 'details' => $details],
            'customer.subscription.created' => ['title' => 'Recurring support started', 'details' => $details],
            'customer.subscription.updated' => ['title' => $this->subscriptionUpdateTitle($object), 'details' => $details],
            'customer.subscription.deleted' => ['title' => 'Recurring support canceled', 'details' => $details],
            'customer.updated' => ['title' => 'Donor billing profile updated', 'details' => $details],
            'charge.refunded' => ['title' => 'Donation refunded', 'details' => $details],
            'charge.dispute.closed' => ($object['status'] ?? null) === 'lost'
                ? ['title' => 'Donation dispute lost', 'details' => $details]
                : null,
            default => null,
        };
    }

    /** @param array<string, mixed> $object */
    private function subscriptionUpdateTitle(array $object): string
    {
        if (($object['cancel_at_period_end'] ?? false) === true) {
            return 'Recurring support cancellation scheduled';
        }

        if (is_array($object['pause_collection'] ?? null)) {
            return 'Recurring support pause scheduled';
        }

        return 'Recurring support updated';
    }

    /** @param array<string, mixed> $object
     * @return array<string, string>
     */
    private function details(array $object): array
    {
        $metadata = is_array($object['metadata'] ?? null) ? $object['metadata'] : [];
        $item = Arr::get($object, 'items.data.0', []);
        $amountCents = (int) ($object['amount_paid'] ?? $object['amount_total'] ?? $object['amount'] ?? $item['price']['unit_amount'] ?? 0);
        $interval = $item['price']['recurring']['interval'] ?? null;
        $intervalCount = max(1, (int) ($item['price']['recurring']['interval_count'] ?? 1));
        $cadence = $interval === 'month' && $intervalCount === 3 ? 'Quarterly' : match ($interval) {
            'month' => 'Monthly',
            'year' => 'Annual',
            default => null,
        };

        return array_filter([
            'Donor' => $metadata['donor_name'] ?? $object['customer_name'] ?? Arr::get($object, 'customer_details.name'),
            'Email' => $object['customer_email'] ?? Arr::get($object, 'customer_details.email'),
            'Amount' => $amountCents > 0 ? '$'.number_format($amountCents / 100, 2) : null,
            'Cadence' => $cadence,
            'Status' => isset($object['status']) ? ucfirst((string) $object['status']) : null,
            'Effective date' => $this->date($item['current_period_end'] ?? $object['period_end'] ?? null),
        ], fn (?string $value): bool => $value !== null && $value !== '');
    }

    private function date(mixed $timestamp): ?string
    {
        return is_numeric($timestamp) ? now()->setTimestamp((int) $timestamp)->format('F j, Y') : null;
    }
}
