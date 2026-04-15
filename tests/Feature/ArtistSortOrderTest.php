<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Discipline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtistSortOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_artists_append_to_the_end_of_the_sort_order(): void
    {
        $firstArtist = Artist::query()->create([
            'name' => 'Bravo Artist',
            'slug' => 'bravo-artist',
            'is_active' => true,
        ]);

        $secondArtist = Artist::query()->create([
            'name' => 'Alpha Artist',
            'slug' => 'alpha-artist',
            'is_active' => true,
        ]);

        $this->assertSame(1, $firstArtist->sort_order);
        $this->assertSame(2, $secondArtist->sort_order);
    }

    public function test_artists_page_uses_sort_order_before_name(): void
    {
        $music = Discipline::query()->where('slug', 'music')->firstOrFail();

        $lastByName = Artist::query()->create([
            'name' => 'Zulu Artist',
            'slug' => 'zulu-artist',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $lastByName->disciplines()->sync([$music->id]);

        $firstByName = Artist::query()->create([
            'name' => 'Alpha Artist',
            'slug' => 'alpha-artist',
            'is_active' => true,
            'sort_order' => 2,
        ]);
        $firstByName->disciplines()->sync([$music->id]);

        $response = $this->get(route('artists'));

        $response->assertOk();
        $response->assertSeeInOrder([$lastByName->name, $firstByName->name]);
    }
}
