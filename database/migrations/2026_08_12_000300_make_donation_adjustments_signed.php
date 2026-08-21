<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('donations') || DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE donations MODIFY amount_cents BIGINT NULL');
    }

    public function down(): void
    {
        // Financial ledger migrations are forward-only. Do not destroy or coerce adjustments.
    }
};
