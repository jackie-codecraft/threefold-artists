<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('subject');
            $table->longText('body');
            $table->enum('recipient_type', ['all', 'custom'])->default('all');
            $table->json('recipient_ids')->nullable();
            $table->enum('status', ['draft', 'sending', 'sent'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->unsignedInteger('total_sent')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletters');
    }
};
