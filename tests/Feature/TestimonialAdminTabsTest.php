<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\TestimonialResource\Pages\ListTestimonials;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TestimonialAdminTabsTest extends TestCase
{
    use RefreshDatabase;

    public function test_testimonial_admin_tabs_filter_records_by_status(): void
    {
        $user = User::factory()->create();

        $activeTestimonial = Testimonial::query()->create([
            'quote' => 'Active testimonial quote',
            'attribution' => 'Active Person',
            'venue_name' => 'Active Venue',
            'is_active' => true,
            'is_featured' => false,
        ]);

        $featuredTestimonial = Testimonial::query()->create([
            'quote' => 'Featured testimonial quote',
            'attribution' => 'Featured Person',
            'venue_name' => 'Featured Venue',
            'is_active' => true,
            'is_featured' => true,
        ]);

        $inactiveTestimonial = Testimonial::query()->create([
            'quote' => 'Inactive testimonial quote',
            'attribution' => 'Inactive Person',
            'venue_name' => 'Inactive Venue',
            'is_active' => false,
            'is_featured' => false,
        ]);

        $this->actingAs($user);

        Livewire::test(ListTestimonials::class)
            ->assertSet('activeTab', 'all')
            ->assertCanSeeTableRecords([$activeTestimonial, $featuredTestimonial, $inactiveTestimonial])
            ->set('activeTab', 'active')
            ->assertCanSeeTableRecords([$activeTestimonial, $featuredTestimonial])
            ->assertCanNotSeeTableRecords([$inactiveTestimonial])
            ->set('activeTab', 'featured')
            ->assertCanSeeTableRecords([$featuredTestimonial])
            ->assertCanNotSeeTableRecords([$activeTestimonial, $inactiveTestimonial])
            ->set('activeTab', 'all')
            ->assertCanSeeTableRecords([$activeTestimonial, $featuredTestimonial, $inactiveTestimonial])
            ->set('activeTab', 'inactive')
            ->assertCanSeeTableRecords([$inactiveTestimonial])
            ->assertCanNotSeeTableRecords([$activeTestimonial, $featuredTestimonial]);
    }
}
