<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('donation_supports')) {
            return;
        }

        Schema::table('donation_supports', function (Blueprint $table): void {
            if (! Schema::hasColumn('donation_supports', 'paused_until')) {
                $table->timestamp('paused_until')->nullable()->after('pause_resumes_at');
            }

            if (! Schema::hasColumn('donation_supports', 'cancel_at_period_end')) {
                $table->boolean('cancel_at_period_end')->default(false)->after('paused_until');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('donation_supports')) {
            return;
        }

        Schema::table('donation_supports', function (Blueprint $table): void {
            $columns = [];

            if (Schema::hasColumn('donation_supports', 'paused_until')) {
                $columns[] = 'paused_until';
            }

            if (Schema::hasColumn('donation_supports', 'cancel_at_period_end')) {
                $columns[] = 'cancel_at_period_end';
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
