<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_settings') || Schema::hasColumn('site_settings', 'show_donations')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table): void {
            $table->boolean('show_donations')->default(true)->after('show_events');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_settings') || ! Schema::hasColumn('site_settings', 'show_donations')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table): void {
            $table->dropColumn('show_donations');
        });
    }
};
