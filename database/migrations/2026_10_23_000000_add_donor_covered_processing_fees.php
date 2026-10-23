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
            // Portion of amount_cents the donor added to cover processing fees.
            // Null means none recorded (no coverage, or a pre-feature payment).
            $table->unsignedInteger('processing_fee_cents')->nullable()->after('amount_cents');
        });

        Schema::table('donation_supports', function (Blueprint $table): void {
            $table->boolean('covers_processing_fees')->default(false)->after('amount_cents');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table): void {
            $table->dropColumn('processing_fee_cents');
        });

        Schema::table('donation_supports', function (Blueprint $table): void {
            $table->dropColumn('covers_processing_fees');
        });
    }
};
