<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pledges', function (Blueprint $table): void {
            $table->boolean('public_acknowledgment_consent')->default(true)->after('message');
        });
    }

    public function down(): void
    {
        Schema::table('pledges', function (Blueprint $table): void {
            $table->dropColumn('public_acknowledgment_consent');
        });
    }
};
