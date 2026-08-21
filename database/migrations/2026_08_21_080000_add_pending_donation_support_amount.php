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
            if (! Schema::hasColumn('donation_supports', 'pending_amount_cents')) {
                $table->unsignedBigInteger('pending_amount_cents')->nullable()->after('amount_cents');
            }

            if (! Schema::hasColumn('donation_supports', 'pending_amount_effective_at')) {
                $table->timestamp('pending_amount_effective_at')->nullable()->after('pending_amount_cents');
            }
        });
    }

    public function down(): void
    {
        // Forward-only: scheduled donor changes are financial audit evidence.
    }
};
