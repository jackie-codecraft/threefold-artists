<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Event;
use App\Models\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventDetailsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_list_links_to_event_details_page(): void
    {
        $event = Event::query()->create([
            'title' => 'Community Theatre Night',
            'description' => 'A full evening of theatre and music for the community.',
            'date' => now()->addWeek()->toDateString(),
            'time' => '18:30:00',
            'venue_name' => 'Eastside Community Center',
            'venue_address' => '1346 South Boyle Avenue, Los Angeles, CA 90023',
            'art_form' => 'theatre',
            'is_public' => true,
        ]);

        $response = $this->get(route('events'));

        $response->assertOk();
        $response->assertSee('View Event');
        $response->assertSee(route('events.show', $event), false);
    }

    public function test_event_details_page_shows_full_public_event_details(): void
    {
        $event = Event::query()->create([
            'title' => 'Community Theatre Night',
            'description' => 'A full evening of theatre and music for the community.',
            'date' => now()->addWeek()->toDateString(),
            'time' => '18:30:00',
            'venue_name' => 'Eastside Community Center',
            'venue_address' => '1346 South Boyle Avenue, Los Angeles, CA 90023',
            'art_form' => 'theatre',
            'is_public' => true,
        ]);

        $response = $this->get(route('events.show', $event));

        $response->assertOk();
        $response->assertSee('Community Theatre Night');
        $response->assertSee('A full evening of theatre and music for the community.');
        $response->assertSee('Eastside Community Center');
        $response->assertSee('6:30 PM');
        $response->assertSee(route('events'), false);
    }

    public function test_event_featured_image_renders_on_event_list_and_detail_pages(): void
    {
        $event = Event::query()->create([
            'title' => 'Featured Fundraiser Concert',
            'description' => 'A music event with a full landing page image.',
            'date' => now()->addWeek()->toDateString(),
            'time' => '19:00:00',
            'venue_name' => 'Theatre Hall',
            'art_form' => 'music',
            'featured_image' => 'https://example.com/event-featured.jpg',
            'is_public' => true,
        ]);

        $this->get(route('events'))
            ->assertOk()
            ->assertSee('https://example.com/event-featured.jpg', false)
            ->assertSee('alt="Featured Fundraiser Concert"', false);

        $this->get(route('events.show', $event))
            ->assertOk()
            ->assertSee('https://example.com/event-featured.jpg', false)
            ->assertSee('Featured Fundraiser Concert');
    }

    public function test_event_list_uses_branded_placeholder_without_featured_image(): void
    {
        Event::query()->create([
            'title' => 'Community Theatre Night',
            'description' => 'A full evening of theatre and music for the community.',
            'date' => now()->addWeek()->toDateString(),
            'venue_name' => 'Eastside Community Center',
            'is_public' => true,
        ]);

        $this->get(route('events'))
            ->assertOk()
            ->assertSee('Threefold Artists');
    }

    public function test_event_details_page_hides_non_public_events(): void
    {
        $event = Event::query()->create([
            'title' => 'Private Care Home Performance',
            'description' => 'Internal event.',
            'date' => now()->addWeek()->toDateString(),
            'venue_name' => 'Private Venue',
            'is_public' => false,
        ]);

        $this->get(route('events.show', $event))->assertNotFound();
    }

    public function test_event_details_page_respects_events_visibility_setting(): void
    {
        SiteSettings::query()->updateOrCreate([], [
            'show_events' => false,
        ]);

        $event = Event::query()->create([
            'title' => 'Hidden Event',
            'description' => 'Should not render.',
            'date' => now()->addWeek()->toDateString(),
            'venue_name' => 'Hidden Venue',
            'is_public' => true,
        ]);

        $this->get(route('events.show', $event))->assertNotFound();
    }
}
