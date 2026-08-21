<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('donation_supports') || Schema::hasColumn('donation_supports', 'pause_collection_behavior')) {
            return;
        }

        Schema::table('donation_supports', function (Blueprint $table): void {
            $table->string('pause_collection_behavior')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        // Forward-only operational migration: never drop persisted support state.
    }
};
