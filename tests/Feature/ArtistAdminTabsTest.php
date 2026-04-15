<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\ArtistResource\Pages\ListArtists;
use App\Models\Artist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ArtistAdminTabsTest extends TestCase
{
    use RefreshDatabase;

    public function test_artist_admin_tabs_filter_records_by_status(): void
    {
        $user = User::factory()->create();

        $activeArtist = Artist::query()->create([
            'name' => 'Active Artist',
            'slug' => 'active-artist',
            'bio' => 'Active bio',
            'is_active' => true,
            'is_featured' => false,
        ]);

        $featuredArtist = Artist::query()->create([
            'name' => 'Featured Artist',
            'slug' => 'featured-artist',
            'bio' => 'Featured bio',
            'is_active' => true,
            'is_featured' => true,
        ]);

        $inactiveArtist = Artist::query()->create([
            'name' => 'Inactive Artist',
            'slug' => 'inactive-artist',
            'bio' => 'Inactive bio',
            'is_active' => false,
            'is_featured' => false,
        ]);

        $this->actingAs($user);

        Livewire::test(ListArtists::class)
            ->assertSet('activeTab', 'all')
            ->assertCanSeeTableRecords([$activeArtist, $featuredArtist, $inactiveArtist])
            ->set('activeTab', 'active')
            ->assertCanSeeTableRecords([$activeArtist, $featuredArtist])
            ->assertCanNotSeeTableRecords([$inactiveArtist])
            ->set('activeTab', 'featured')
            ->assertCanSeeTableRecords([$featuredArtist])
            ->assertCanNotSeeTableRecords([$activeArtist, $inactiveArtist])
            ->set('activeTab', 'all')
            ->assertCanSeeTableRecords([$activeArtist, $featuredArtist, $inactiveArtist])
            ->set('activeTab', 'inactive')
            ->assertCanSeeTableRecords([$inactiveArtist])
            ->assertCanNotSeeTableRecords([$activeArtist, $featuredArtist]);
    }

    public function test_artist_admin_page_shows_reorder_trigger(): void
    {
        $user = User::factory()->create();

        Artist::query()->create([
            'name' => 'Active Artist',
            'slug' => 'active-artist',
            'bio' => 'Active bio',
            'is_active' => true,
            'is_featured' => false,
        ]);

        $this->actingAs($user);

        Livewire::test(ListArtists::class)
            ->assertSee('Reorder artists');
    }
}
