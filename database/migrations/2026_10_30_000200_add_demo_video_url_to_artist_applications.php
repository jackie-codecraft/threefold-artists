<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artist_applications', function (Blueprint $table): void {
            $table->string('demo_video_url', 2048)->nullable()->after('availability');
        });
    }

    public function down(): void
    {
        Schema::table('artist_applications', function (Blueprint $table): void {
            $table->dropColumn('demo_video_url');
        });
    }
};
