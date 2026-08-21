<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donation_supports', function (Blueprint $table): void {
            if (! Schema::hasColumn('donation_supports', 'pending_interval')) {
                $table->string('pending_interval')->nullable()->after('pending_amount_effective_at');
            }

            if (! Schema::hasColumn('donation_supports', 'pending_interval_count')) {
                $table->unsignedInteger('pending_interval_count')->nullable()->after('pending_interval');
            }

            if (! Schema::hasColumn('donation_supports', 'stripe_subscription_schedule_id')) {
                $table->string('stripe_subscription_schedule_id')->nullable()->after('stripe_subscription_id');
            }
        });
    }

    public function down(): void
    {
        // Forward-only: pending donor changes are financial audit evidence.
    }
};
