<?php

declare(strict_types=1);

use App\Support\DisciplineOptions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplines', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('artist_discipline', function (Blueprint $table) {
            $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
            $table->foreignId('discipline_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['artist_id', 'discipline_id']);
        });

        $now = now();

        foreach (DisciplineOptions::labels() as $slug => $name) {
            DB::table('disciplines')->insert([
                'slug' => $slug,
                'name' => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $disciplineIds = DB::table('disciplines')->pluck('id', 'slug');

        DB::table('artists')
            ->select(['id', 'discipline'])
            ->orderBy('id')
            ->get()
            ->each(function (object $artist) use ($disciplineIds, $now): void {
                $disciplineId = $disciplineIds[$artist->discipline] ?? null;

                if ($disciplineId === null) {
                    return;
                }

                DB::table('artist_discipline')->insert([
                    'artist_id' => $artist->id,
                    'discipline_id' => $disciplineId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            });

        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn('discipline');
        });
    }

    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->string('discipline')->nullable()->after('photo');
        });

        $artistDisciplines = DB::table('artist_discipline')
            ->join('disciplines', 'disciplines.id', '=', 'artist_discipline.discipline_id')
            ->select('artist_discipline.artist_id', 'disciplines.slug')
            ->orderBy('artist_discipline.artist_id')
            ->get()
            ->groupBy('artist_id');

        foreach ($artistDisciplines as $artistId => $disciplines) {
            DB::table('artists')->where('id', $artistId)->update([
                'discipline' => $disciplines->first()->slug,
            ]);
        }

        Schema::dropIfExists('artist_discipline');
        Schema::dropIfExists('disciplines');
    }
};
