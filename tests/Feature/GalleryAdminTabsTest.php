<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\GalleryItemResource\Pages\ListGalleryItems;
use App\Models\GalleryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GalleryAdminTabsTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_admin_tabs_filter_records_by_status(): void
    {
        $user = User::factory()->create();

        $activeItem = GalleryItem::query()->create([
            'title' => 'Active Gallery Item',
            'type' => 'photo',
            'is_active' => true,
            'is_featured' => false,
        ]);

        $featuredItem = GalleryItem::query()->create([
            'title' => 'Featured Gallery Item',
            'type' => 'photo',
            'is_active' => true,
            'is_featured' => true,
        ]);

        $inactiveItem = GalleryItem::query()->create([
            'title' => 'Inactive Gallery Item',
            'type' => 'photo',
            'is_active' => false,
            'is_featured' => false,
        ]);

        $this->actingAs($user);

        Livewire::test(ListGalleryItems::class)
            ->assertSet('activeTab', 'active')
            ->assertCanSeeTableRecords([$activeItem, $featuredItem])
            ->assertCanNotSeeTableRecords([$inactiveItem])
            ->set('activeTab', 'featured')
            ->assertCanSeeTableRecords([$featuredItem])
            ->assertCanNotSeeTableRecords([$activeItem, $inactiveItem])
            ->set('activeTab', 'all')
            ->assertCanSeeTableRecords([$activeItem, $featuredItem, $inactiveItem])
            ->set('activeTab', 'inactive')
            ->assertCanSeeTableRecords([$inactiveItem])
            ->assertCanNotSeeTableRecords([$activeItem, $featuredItem]);
    }
}
