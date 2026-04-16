<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_only_shows_active_featured_testimonials(): void
    {
        Testimonial::query()->create([
            'quote' => 'Visible featured testimonial',
            'attribution' => 'Visible Person',
            'venue_name' => 'Visible Venue',
            'is_active' => true,
            'is_featured' => true,
        ]);

        Testimonial::query()->create([
            'quote' => 'Hidden inactive featured testimonial',
            'attribution' => 'Hidden Featured Person',
            'venue_name' => 'Hidden Featured Venue',
            'is_active' => false,
            'is_featured' => true,
        ]);

        Testimonial::query()->create([
            'quote' => 'Hidden active non-featured testimonial',
            'attribution' => 'Hidden Non Featured Person',
            'venue_name' => 'Hidden Non Featured Venue',
            'is_active' => true,
            'is_featured' => false,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertViewHas('testimonials', fn ($testimonials) => $testimonials->pluck('attribution')->all() === ['Visible Person']);
        $response->assertSee('Visible featured testimonial');
        $response->assertDontSee('Hidden inactive featured testimonial');
        $response->assertDontSee('Hidden active non-featured testimonial');
    }

    public function test_impact_page_only_shows_active_testimonials(): void
    {
        Testimonial::query()->create([
            'quote' => 'Visible impact testimonial',
            'attribution' => 'Visible Impact Person',
            'venue_name' => 'Visible Impact Venue',
            'is_active' => true,
            'is_featured' => false,
        ]);

        Testimonial::query()->create([
            'quote' => 'Hidden inactive impact testimonial',
            'attribution' => 'Hidden Impact Person',
            'venue_name' => 'Hidden Impact Venue',
            'is_active' => false,
            'is_featured' => false,
        ]);

        $response = $this->get(route('impact'));

        $response->assertOk();
        $response->assertViewHas('testimonials', fn ($testimonials) => $testimonials->pluck('attribution')->all() === ['Visible Impact Person']);
        $response->assertSee('Visible impact testimonial');
        $response->assertDontSee('Hidden inactive impact testimonial');
    }
}
