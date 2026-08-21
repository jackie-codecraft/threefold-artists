<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donors', function (Blueprint $table): void {
            $table->boolean('public_recognition_consent')->nullable()->index();
        });

        Schema::table('donations', function (Blueprint $table): void {
            $table->boolean('public_recognition_consent')->nullable()->index();
        });
    }

    public function down(): void
    {
        // Recognition consent is financial/privacy audit evidence; migration is forward-only.
    }
};
