<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('donation_supports')) {
            return;
        }

        Schema::table('donation_supports', function (Blueprint $table): void {
            if (! Schema::hasColumn('donation_supports', 'interval_count')) {
                $table->unsignedInteger('interval_count')->default(1)->after('interval');
            }
        });
    }

    public function down(): void
    {
        // Financial support records are forward-only once deployed.
    }
};
