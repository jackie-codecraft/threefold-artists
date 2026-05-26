<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Event;
use App\Models\GalleryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_detail_page_shows_related_gallery_preview(): void
    {
        $event = Event::query()->create([
            'title' => 'Spring Benefit',
            'description' => 'A community performance.',
            'date' => now()->addWeek()->toDateString(),
            'venue_name' => 'Main Stage',
            'is_public' => true,
        ]);

        $event->galleryItems()->create([
            'title' => 'Rehearsal Moment',
            'type' => 'photo',
            'is_active' => true,
        ]);

        GalleryItem::query()->create([
            'title' => 'Unrelated Gallery Item',
            'type' => 'photo',
            'is_active' => true,
        ]);

        $response = $this->get(route('events.show', $event));

        $response->assertOk();
        $response->assertSee('Event Gallery');
        $response->assertSee('Rehearsal Moment');
        $response->assertDontSee('Unrelated Gallery Item');
        $response->assertSee('related_type=event', false);
        $response->assertSee('related_id='.$event->id, false);
    }

    public function test_gallery_page_filters_by_related_event(): void
    {
        $event = Event::query()->create([
            'title' => 'Community Concert',
            'date' => now()->addWeek()->toDateString(),
            'venue_name' => 'Community Hall',
            'is_public' => true,
        ]);

        $event->galleryItems()->create([
            'title' => 'Concert Photo',
            'type' => 'photo',
            'is_active' => true,
        ]);

        GalleryItem::query()->create([
            'title' => 'General Gallery Photo',
            'type' => 'photo',
            'is_active' => true,
        ]);

        $response = $this->get(route('gallery', ['related_type' => 'event', 'related_id' => $event->id]));

        $response->assertOk();
        $response->assertViewHas('items', fn ($items) => $items->pluck('title')->all() === ['Concert Photo']);
        $response->assertSee('Concert Photo');
        $response->assertDontSee('General Gallery Photo');
    }

    public function test_gallery_page_filters_by_related_blog_post(): void
    {
        $post = BlogPost::query()->create([
            'title' => 'Behind the Curtain',
            'slug' => 'behind-the-curtain',
            'content' => '<p>Story content.</p>',
            'published_at' => now(),
            'is_published' => true,
        ]);

        $post->galleryItems()->create([
            'title' => 'Story Photo',
            'type' => 'photo',
            'is_active' => true,
        ]);

        GalleryItem::query()->create([
            'title' => 'Other Photo',
            'type' => 'photo',
            'is_active' => true,
        ]);

        $response = $this->get(route('gallery', ['related_type' => 'blog_post', 'related_id' => $post->id]));

        $response->assertOk();
        $response->assertViewHas('items', fn ($items) => $items->pluck('title')->all() === ['Story Photo']);
        $response->assertSee('Story Photo');
        $response->assertDontSee('Other Photo');
    }

    public function test_event_gallery_preview_renders_related_videos_as_video_elements(): void
    {
        Storage::fake('public');

        $event = Event::query()->create([
            'title' => 'Video Event',
            'date' => now()->addWeek()->toDateString(),
            'venue_name' => 'Studio',
            'is_public' => true,
        ]);

        $video = $event->galleryItems()->create([
            'title' => 'Performance Clip',
            'type' => 'video',
            'is_active' => true,
        ]);

        $video->addMedia(UploadedFile::fake()->create('performance.mp4', 128, 'video/mp4'))
            ->toMediaCollection('media', 'public');

        $response = $this->get(route('events.show', $event));

        $response->assertOk();
        $response->assertSee('<video', false);
        $response->assertSee('performance.mp4', false);
    }
}
