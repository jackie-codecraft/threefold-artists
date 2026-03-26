<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->decimal('amount', 10, 2);
            $table->boolean('is_recurring')->default(false);
            $table->boolean('is_anonymous')->default(false);
            $table->string('stripe_payment_id')->nullable();
            $table->timestamp('receipt_sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
