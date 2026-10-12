<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\DonationReceipt;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * Records gifts received outside Stripe (cash, check, etc.) into the same ledger
 * that drives totals, statements, the donor portal and the donor wall.
 *
 * Offline rows are never edited: mistakes are voided with a reason and re-entered.
 */
class OfflineDonationService
{
    /**
     * @param  array{
     *     donor_id: int|string,
     *     amount: int|string,
     *     payment_method: string,
     *     check_number?: string|null,
     *     received_on: string|\DateTimeInterface,
     *     is_anonymous?: bool,
     *     public_recognition_consent?: bool,
     *     admin_note?: string|null,
     *     send_receipt?: bool,
     * }  $data
     */
    public function record(array $data, User $recordedBy): Donation
    {
        $donor = Donor::query()->findOrFail($data['donor_id']);
        $dollars = (int) preg_replace('/\D/', '', (string) $data['amount']);
        $method = $data['payment_method'];
        $receivedOn = Carbon::parse($data['received_on'])->startOfDay();
        $anonymous = (bool) ($data['is_anonymous'] ?? false);
        $consent = ! $anonymous && (bool) ($data['public_recognition_consent'] ?? false);

        if ($dollars < 1) {
            throw ValidationException::withMessages(['amount' => 'Enter a whole-dollar amount of at least $1.']);
        }

        if (! array_key_exists($method, Donation::PAYMENT_METHODS)) {
            throw ValidationException::withMessages(['payment_method' => 'Choose cash, check, or other.']);
        }

        if ($receivedOn->isAfter(now()->endOfDay())) {
            throw ValidationException::withMessages(['received_on' => 'The date received cannot be in the future.']);
        }

        $donation = DB::transaction(function () use ($donor, $dollars, $method, $data, $receivedOn, $anonymous, $consent, $recordedBy): Donation {
            if ($consent && ! $donor->public_recognition_consent) {
                $donor->update(['public_recognition_consent' => true]);
            }

            return Donation::query()->create([
                'donor_id' => $donor->id,
                'donor_name' => $donor->name,
                'donor_email' => $donor->email,
                'amount' => $dollars,
                'amount_cents' => $dollars * 100,
                'currency' => 'usd',
                'status' => 'paid',
                'source' => Donation::SOURCE_MANUAL,
                'payment_method' => $method,
                'check_number' => $method === 'check' ? (trim((string) ($data['check_number'] ?? '')) ?: null) : null,
                'admin_note' => trim((string) ($data['admin_note'] ?? '')) ?: null,
                'recorded_by_user_id' => $recordedBy->id,
                'is_recurring' => false,
                'is_anonymous' => $anonymous,
                'public_recognition_consent' => $consent,
                'paid_at' => $receivedOn,
            ]);
        });

        if (($data['send_receipt'] ?? false) && filled($donor->email)) {
            Mail::to($donor->email)->send(new DonationReceipt($donation));
            $donation->update(['receipt_sent_at' => now()]);
        }

        return $donation;
    }

    public function void(Donation $donation, string $reason, User $voidedBy): Donation
    {
        $reason = trim($reason);

        if (! $donation->isVoidable()) {
            throw ValidationException::withMessages(['reason' => 'Only active offline donations can be voided.']);
        }

        if ($reason === '') {
            throw ValidationException::withMessages(['reason' => 'A reason is required to void a donation.']);
        }

        // Conditional update so two admins voiding at once cannot double-process.
        $updated = Donation::query()
            ->whereKey($donation->getKey())
            ->where('source', Donation::SOURCE_MANUAL)
            ->where('status', 'paid')
            ->whereNull('voided_at')
            ->update([
                'status' => 'voided',
                'voided_at' => now(),
                'voided_by_user_id' => $voidedBy->id,
                'void_reason' => $reason,
                'updated_at' => now(),
            ]);

        if ($updated !== 1) {
            throw ValidationException::withMessages(['reason' => 'This donation was already voided.']);
        }

        return $donation->refresh();
    }
}
