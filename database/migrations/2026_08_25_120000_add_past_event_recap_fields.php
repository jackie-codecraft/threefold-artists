<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table): void {
            $table->text('recap')->nullable()->after('description');
            $table->boolean('is_past_published')->default(false)->after('is_public');
        });

        Schema::table('testimonials', function (Blueprint $table): void {
            $table->foreignId('event_id')->nullable()->after('venue_name')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('event_id');
        });

        Schema::table('events', function (Blueprint $table): void {
            $table->dropColumn(['recap', 'is_past_published']);
        });
    }
};
