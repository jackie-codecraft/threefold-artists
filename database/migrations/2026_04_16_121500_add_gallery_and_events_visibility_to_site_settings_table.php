<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('site_settings', 'show_gallery')) {
                $table->boolean('show_gallery')->default(true)->after('show_impact');
            }

            if (! Schema::hasColumn('site_settings', 'show_events')) {
                $table->boolean('show_events')->default(true)->after('show_gallery');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table): void {
            $columns = [];

            if (Schema::hasColumn('site_settings', 'show_gallery')) {
                $columns[] = 'show_gallery';
            }

            if (Schema::hasColumn('site_settings', 'show_events')) {
                $columns[] = 'show_events';
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
