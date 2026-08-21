<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donors', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('stripe_customer_id')->nullable()->unique();
            $table->timestamps();
        });

        Schema::create('donation_supports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('donor_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('stripe_subscription_id')->nullable()->unique();
            $table->string('stripe_customer_id')->nullable()->unique();
            $table->string('stripe_price_id')->nullable()->index();
            $table->unsignedBigInteger('amount_cents');
            $table->string('currency', 3)->default('usd');
            $table->string('interval')->nullable();
            $table->string('status')->default('active')->index();
            $table->string('pause_collection_behavior')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->timestamp('pause_resumes_at')->nullable();
            $table->timestamp('current_period_starts_at')->nullable();
            $table->timestamp('current_period_ends_at')->nullable();
            $table->timestamps();
        });

        Schema::table('donations', function (Blueprint $table): void {
            $table->foreignId('donor_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('donation_support_id')->nullable()->after('donor_id')->constrained()->nullOnDelete();
            $table->string('status')->default('legacy')->index()->after('amount');
            $table->unsignedBigInteger('amount_cents')->nullable()->after('amount');
            $table->string('currency', 3)->nullable()->after('amount_cents');
            $table->string('stripe_checkout_session_id')->nullable()->unique()->after('stripe_payment_id');
            $table->string('stripe_payment_intent_id')->nullable()->unique()->after('stripe_checkout_session_id');
            $table->string('stripe_invoice_id')->nullable()->unique()->after('stripe_payment_intent_id');
            $table->string('stripe_charge_id')->nullable()->unique()->after('stripe_invoice_id');
            $table->timestamp('paid_at')->nullable()->after('receipt_sent_at');
            $table->timestamp('refunded_at')->nullable()->after('paid_at');
            $table->timestamp('disputed_at')->nullable()->after('refunded_at');
        });

        Schema::create('stripe_webhook_events', function (Blueprint $table): void {
            $table->id();
            $table->string('stripe_event_id')->unique();
            $table->string('event_type')->index();
            $table->string('api_version')->nullable();
            $table->boolean('livemode')->default(false);
            $table->json('payload');
            $table->timestamp('received_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->text('processing_error')->nullable();
            $table->timestamps();
        });

        $this->backfillLegacyDonors();
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_webhook_events');

        Schema::table('donations', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('donation_support_id');
            $table->dropConstrainedForeignId('donor_id');
            $table->dropUnique(['stripe_checkout_session_id']);
            $table->dropUnique(['stripe_payment_intent_id']);
            $table->dropUnique(['stripe_invoice_id']);
            $table->dropUnique(['stripe_charge_id']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'status',
                'amount_cents',
                'currency',
                'stripe_checkout_session_id',
                'stripe_payment_intent_id',
                'stripe_invoice_id',
                'stripe_charge_id',
                'paid_at',
                'refunded_at',
                'disputed_at',
            ]);
        });

        Schema::dropIfExists('donation_supports');
        Schema::dropIfExists('donors');
    }

    private function backfillLegacyDonors(): void
    {
        DB::table('donations')
            ->select(['id', 'donor_name', 'donor_email'])
            ->whereNotNull('donor_email')
            ->whereRaw("trim(donor_email) <> ''")
            ->orderBy('id')
            ->each(function (object $donation): void {
                $email = mb_strtolower(trim((string) $donation->donor_email));
                $now = now();

                DB::table('donors')->updateOrInsert(
                    ['email' => $email],
                    [
                        'name' => $donation->donor_name,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ],
                );

                DB::table('donations')
                    ->where('id', $donation->id)
                    ->update(['donor_id' => DB::table('donors')->where('email', $email)->value('id')]);
            });
    }
};
