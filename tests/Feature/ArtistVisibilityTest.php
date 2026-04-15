<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Discipline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtistVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_artists_index_only_shows_active_artists_and_multiple_disciplines(): void
    {
        $music = Discipline::query()->where('slug', 'music')->firstOrFail();
        $dance = Discipline::query()->where('slug', 'dance')->firstOrFail();

        $activeArtist = Artist::create([
            'name' => 'Active Artist',
            'slug' => 'active-artist',
            'is_active' => true,
        ]);
        $activeArtist->disciplines()->sync([$music->id, $dance->id]);

        $inactiveArtist = Artist::create([
            'name' => 'Inactive Artist',
            'slug' => 'inactive-artist',
            'is_active' => false,
        ]);
        $inactiveArtist->disciplines()->sync([$dance->id]);

        $response = $this->get(route('artists'));

        $response->assertOk();
        $response->assertSee($activeArtist->name);
        $response->assertSee('Music • Dance', false);
        $response->assertDontSee($inactiveArtist->name);
    }

    public function test_inactive_artist_detail_pages_are_not_publicly_accessible(): void
    {
        $theatre = Discipline::query()->where('slug', 'theatre')->firstOrFail();
        $fineArts = Discipline::query()->where('slug', 'fine_arts')->firstOrFail();

        $activeArtist = Artist::create([
            'name' => 'Visible Artist',
            'slug' => 'visible-artist',
            'is_active' => true,
        ]);
        $activeArtist->disciplines()->sync([$theatre->id]);

        $inactiveArtist = Artist::create([
            'name' => 'Hidden Artist',
            'slug' => 'hidden-artist',
            'is_active' => false,
        ]);
        $inactiveArtist->disciplines()->sync([$fineArts->id]);

        $this->get(route('artists.show', $activeArtist))->assertOk()->assertSee('Theatre');
        $this->get('/artists/'.$inactiveArtist->slug)->assertNotFound();
    }
}
