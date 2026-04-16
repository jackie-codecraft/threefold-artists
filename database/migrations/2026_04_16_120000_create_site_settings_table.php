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
        Schema::create('site_settings', function (Blueprint $table): void {
            $table->id();
            $table->boolean('show_blog')->default(true);
            $table->boolean('show_impact')->default(true);
            $table->string('contact_email')->nullable();
            $table->string('donations_email')->nullable();
            $table->timestamps();
        });

        DB::table('site_settings')->insert([
            'show_blog' => true,
            'show_impact' => true,
            'contact_email' => 'hello@threefoldartists.org',
            'donations_email' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
