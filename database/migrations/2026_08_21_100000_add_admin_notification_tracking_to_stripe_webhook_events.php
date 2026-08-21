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
        if (! Schema::hasTable('stripe_webhook_events') || Schema::hasColumn('stripe_webhook_events', 'admin_notified_at')) {
            return;
        }

        Schema::table('stripe_webhook_events', function (Blueprint $table): void {
            $table->timestamp('admin_notified_at')->nullable()->after('processed_at');
        });

        // Existing sandbox/audit events predate admin notifications and must not
        // generate a retrospective mailbox dump when they are replayed.
        DB::table('stripe_webhook_events')
            ->whereNotNull('processed_at')
            ->update(['admin_notified_at' => DB::raw('processed_at')]);
    }

    public function down(): void
    {
        // Forward-only: notification delivery markers are operational audit data.
    }
};
