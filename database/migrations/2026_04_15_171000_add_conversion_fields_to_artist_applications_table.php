<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artist_applications', function (Blueprint $table) {
            $table->foreignId('converted_artist_id')->nullable()->after('status')->constrained('artists')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('converted_artist_id');
            $table->timestamp('converted_at')->nullable()->after('approved_at');
        });

        DB::table('artist_applications')->where('status', 'accepted')->update(['status' => 'approved']);
        DB::table('artist_applications')->where('status', 'replied')->update(['status' => 'reviewed']);
    }

    public function down(): void
    {
        DB::table('artist_applications')->where('status', 'approved')->update(['status' => 'accepted']);

        Schema::table('artist_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('converted_artist_id');
            $table->dropColumn(['approved_at', 'converted_at']);
        });
    }
};
