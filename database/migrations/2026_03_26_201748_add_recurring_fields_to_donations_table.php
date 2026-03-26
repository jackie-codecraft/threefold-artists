<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('donations', 'stripe_subscription_id')) {
            return;
        }

        Schema::table('donations', function (Blueprint $table) {
            $table->string('stripe_subscription_id')->nullable()->after('stripe_payment_id');
            $table->string('recurring_interval')->nullable()->after('is_recurring');
            $table->timestamp('cancelled_at')->nullable()->after('receipt_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['stripe_subscription_id', 'recurring_interval', 'cancelled_at']);
        });
    }
};
