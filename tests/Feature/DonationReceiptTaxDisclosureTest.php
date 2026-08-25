<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\DonationReceipt;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationReceiptTaxDisclosureTest extends TestCase
{
    use RefreshDatabase;

    public function test_receipt_renders_approved_identity_tax_disclosures_and_transaction_details(): void
    {
        $donor = Donor::query()->create([
            'name' => 'Receipt Supporter',
            'email' => 'receipt@example.com',
        ]);
        $donation = Donation::query()->create([
            'donor_id' => $donor->id,
            'donor_name' => $donor->name,
            'donor_email' => $donor->email,
            'amount' => 125,
            'amount_cents' => 12_500,
            'currency' => 'usd',
            'status' => 'paid',
            'stripe_payment_intent_id' => 'pi_receipt_123',
            'paid_at' => now()->setDate(2026, 8, 12),
        ]);

        $html = (new DonationReceipt($donation))->render();

        $this->assertStringContainsString('Threefold Artists, Inc.', $html);
        $this->assertStringContainsString('85-0567934', $html);
        $this->assertStringContainsString('14014 Moorpark Street', $html);
        $this->assertStringContainsString('Suite 308', $html);
        $this->assertStringContainsString('Sherman Oaks, CA 91423', $html);
        $this->assertStringContainsString('501(c)(3) nonprofit charitable organization', $html);
        $this->assertStringContainsString('Contributions to Threefold Artists, Inc. are tax-deductible to the fullest extent permitted by law.', $html);
        $this->assertStringContainsString('No goods or services were provided in exchange for this contribution.', $html);
        $this->assertStringContainsString('pi_receipt_123', $html);
        $this->assertStringContainsString('August 12, 2026', $html);
        $this->assertStringContainsString('$125.00', $html);
        $this->assertStringContainsString('Thank you for helping keep theatre alive.', $html);
        $this->assertStringContainsString('color: #C9A84C; font-family: Georgia, serif; font-size: 18px; font-style: italic;', $html);
        $this->assertStringContainsString('Tax information', $html);
        $this->assertStringContainsString('text-align: left', $html);
        $this->assertStringContainsString('Need to manage your donation?', $html);
        $this->assertStringContainsString('Manage My Donations', $html);
        $this->assertStringContainsString(route('donor-access.request'), $html);
    }
}
