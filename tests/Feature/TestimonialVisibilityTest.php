<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_does_not_render_testimonials(): void
    {
        Testimonial::query()->create([
            'quote' => 'Impact page testimonial only',
            'attribution' => 'Visible Person',
            'venue_name' => 'Visible Venue',
            'is_active' => true,
            'is_featured' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('What People Say');
        $response->assertDontSee('Impact page testimonial only');
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
        $response->assertSee('What People Say');
        $response->assertSee('Visible impact testimonial');
        $response->assertDontSee('Hidden inactive impact testimonial');
    }
}
