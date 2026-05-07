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
            if (! Schema::hasColumn('site_settings', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('donations_email');
            }

            if (! Schema::hasColumn('site_settings', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('facebook_url');
            }

            if (! Schema::hasColumn('site_settings', 'youtube_url')) {
                $table->string('youtube_url')->nullable()->after('instagram_url');
            }

            if (! Schema::hasColumn('site_settings', 'tiktok_url')) {
                $table->string('tiktok_url')->nullable()->after('youtube_url');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table): void {
            $columns = array_values(array_filter([
                Schema::hasColumn('site_settings', 'facebook_url') ? 'facebook_url' : null,
                Schema::hasColumn('site_settings', 'instagram_url') ? 'instagram_url' : null,
                Schema::hasColumn('site_settings', 'youtube_url') ? 'youtube_url' : null,
                Schema::hasColumn('site_settings', 'tiktok_url') ? 'tiktok_url' : null,
            ]));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
