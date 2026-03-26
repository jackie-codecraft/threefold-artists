<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_requests', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name');
            $table->string('venue_type');
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->integer('audience_size')->nullable();
            $table->string('audience_demographics')->nullable();
            $table->string('preferred_art_form')->nullable();
            $table->text('preferred_dates')->nullable();
            $table->text('accessibility_requirements')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'scheduled', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_requests');
    }
};
