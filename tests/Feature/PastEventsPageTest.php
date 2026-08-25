<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PastEventsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_past_events_archive_shows_only_publicly_published_past_events(): void
    {
        $published = $this->pastEvent(['title' => 'Published Community Concert', 'is_past_published' => true]);
        $this->pastEvent(['title' => 'Draft Community Concert']);
        Event::query()->create([
            'title' => 'Future Community Concert',
            'date' => now()->addWeek()->toDateString(),
            'venue_name' => 'Community Hall',
            'is_public' => true,
            'is_past_published' => true,
        ]);

        $this->get(route('events.past'))
            ->assertOk()
            ->assertSee('Published Community Concert')
            ->assertSee(route('events.show', $published), false)
            ->assertDontSee('Draft Community Concert')
            ->assertDontSee('Future Community Concert');
    }

    public function test_unpublished_past_event_is_not_publicly_accessible(): void
    {
        $event = $this->pastEvent();

        $this->get(route('events.show', $event))->assertNotFound();
    }

    public function test_published_past_event_shows_recap_and_active_linked_testimonials(): void
    {
        $event = $this->pastEvent([
            'title' => 'Theatre in the Park',
            'recap' => 'A joyful afternoon of music, movement, and stories.',
            'is_past_published' => true,
        ]);

        Testimonial::query()->create([
            'quote' => 'My family is still talking about it.',
            'attribution' => 'Community Guest',
            'event_id' => $event->id,
            'is_active' => true,
            'is_featured' => false,
        ]);
        Testimonial::query()->create([
            'quote' => 'This should stay private.',
            'attribution' => 'Hidden Guest',
            'event_id' => $event->id,
            'is_active' => false,
            'is_featured' => false,
        ]);

        $this->get(route('events.show', $event))
            ->assertOk()
            ->assertSee('Event Recap')
            ->assertSee('A joyful afternoon of music, movement, and stories.')
            ->assertSee('In Their Words')
            ->assertSee('My family is still talking about it.')
            ->assertDontSee('This should stay private.');
    }

    private function pastEvent(array $overrides = []): Event
    {
        return Event::query()->create(array_merge([
            'title' => 'Past Community Performance',
            'description' => 'A past event.',
            'date' => now()->subDay()->toDateString(),
            'venue_name' => 'Community Hall',
            'is_public' => true,
            'is_past_published' => false,
        ], $overrides));
    }
}
