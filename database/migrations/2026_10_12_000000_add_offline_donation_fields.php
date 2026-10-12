<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table): void {
            $table->string('source', 20)->default('stripe')->index()->after('status');
            $table->string('payment_method', 20)->nullable()->after('source');
            $table->string('check_number', 50)->nullable()->after('payment_method');
            $table->text('admin_note')->nullable()->after('check_number');
            $table->foreignId('recorded_by_user_id')->nullable()->after('admin_note')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('voided_at')->nullable()->after('cancelled_at');
            $table->foreignId('voided_by_user_id')->nullable()->after('voided_at')
                ->constrained('users')->nullOnDelete();
            $table->text('void_reason')->nullable()->after('voided_by_user_id');
        });

        // Cash/check donors may not share an email address.
        Schema::table('donors', function (Blueprint $table): void {
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('voided_by_user_id');
            $table->dropConstrainedForeignId('recorded_by_user_id');
            $table->dropIndex(['source']);
            $table->dropColumn(['source', 'payment_method', 'check_number', 'admin_note', 'voided_at', 'void_reason']);
        });
    }
};
