<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table): void {
            // This feature migration was first applied before charge reconciliation
            // fields were finalized; retain the forward repair for stateful previews.
            if (! Schema::hasColumn('donations', 'stripe_charge_id')) {
                $table->string('stripe_charge_id')->nullable()->unique();
            }

            if (! Schema::hasColumn('donations', 'adjustment_for_donation_id')) {
                $table->foreignId('adjustment_for_donation_id')
                    ->nullable()
                    ->constrained('donations')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('donations', 'stripe_refund_id')) {
                $table->string('stripe_refund_id')->nullable()->unique();
            }

            if (! Schema::hasColumn('donations', 'stripe_dispute_id')) {
                $table->string('stripe_dispute_id')->nullable()->unique();
            }
        });
    }

    public function down(): void
    {
        // Financial ledger migrations are forward-only. Never drop payment evidence.
    }
};
