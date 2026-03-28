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
            $table->text('reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->text('internal_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('artist_applications', function (Blueprint $table): void {
            $table->dropColumn(['reply', 'replied_at', 'internal_notes']);
        });
    }
};
