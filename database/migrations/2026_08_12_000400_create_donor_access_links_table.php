<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donor_access_links', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('donor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email')->nullable()->index();
            $table->string('token_hash', 64)->unique();
            $table->string('purpose')->default('portal');
            $table->timestamp('expires_at')->index();
            $table->timestamp('used_at')->nullable()->index();
            $table->timestamps();

            $table->index(['donor_id', 'purpose', 'used_at']);
        });
    }

    public function down(): void
    {
        // Donor access records are audit/security evidence; migration is forward-only.
    }
};
