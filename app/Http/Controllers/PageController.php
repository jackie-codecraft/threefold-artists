<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\BlogPost;
use App\Models\Donation;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\ImpactMetric;
use App\Models\LeadershipMember;
use App\Models\SiteSettings;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home()
    {
        $metrics = ImpactMetric::orderBy('sort_order')->get();
        $featuredTestimonial = Testimonial::query()->active()->featured()->oldest()->first();
        $upcomingEvents = Event::upcoming()->public()->take(3)->get();

        return view('pages.home', compact('metrics', 'featuredTestimonial', 'upcomingEvents'));
    }

    public function about()
    {
        $leadershipMembers = LeadershipMember::query()->active()->ordered()->get();

        return view('pages.about', compact('leadershipMembers'));
    }

    public function whatWeDo()
    {
        return view('pages.what-we-do');
    }

    public function events()
    {
        abort_unless(SiteSettings::current()->eventsEnabled(), 404);

        $events = Event::upcoming()->public()->paginate(12);
        $allEvents = Event::public()
            ->whereDate('date', '>=', now()->startOfMonth()->subMonths(1))
            ->whereDate('date', '<=', now()->endOfMonth()->addMonths(2))
            ->get()
            ->map(fn (Event $event): array => [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'date' => $event->date,
                'time' => $event->time,
                'venue_name' => $event->venue_name,
                'venue_address' => $event->venue_address,
                'art_form' => $event->art_form,
                'detail_url' => route('events.show', $event),
            ]);

        return view('pages.events', compact('events', 'allEvents'));
    }

    public function pastEvents()
    {
        abort_unless(SiteSettings::current()->eventsEnabled(), 404);

        $events = Event::pastPublished()->public()->paginate(12);

        return view('pages.past-events', compact('events'));
    }

    public function eventShow(Event $event): View
    {
        abort_unless(SiteSettings::current()->eventsEnabled(), 404);
        abort_unless($event->is_public, 404);

        $isPastEvent = $event->date->isBefore(now()->startOfDay());
        abort_if($isPastEvent && ! $event->is_past_published, 404);

        $galleryItems = $event->galleryItems()
            ->active()
            ->latest()
            ->when(! $isPastEvent, fn ($query) => $query->take(4))
            ->get();
        $testimonials = $event->testimonials()->active()->latest()->get();

        return view('pages.event-show', compact('event', 'galleryItems', 'isPastEvent', 'testimonials'));
    }

    public function gallery(Request $request)
    {
        abort_unless(SiteSettings::current()->galleryEnabled(), 404);

        $selectedArtForm = $request->string('art_form')->toString();
        $selectedRelatedType = $request->string('related_type')->toString();
        $selectedRelatedId = $request->integer('related_id');

        $items = GalleryItem::query()
            ->active()
            ->when($selectedArtForm !== '', fn ($query) => $query->where('art_form', $selectedArtForm))
            ->when(
                in_array($selectedRelatedType, ['event', 'blog_post'], true) && $selectedRelatedId > 0,
                fn ($query) => $query
                    ->where('galleryable_type', $selectedRelatedType === 'event' ? Event::class : BlogPost::class)
                    ->where('galleryable_id', $selectedRelatedId)
            )
            ->latest()
            ->get();

        $events = Event::query()
            ->public()
            ->whereHas('galleryItems', fn ($query) => $query->active())
            ->orderByDesc('date')
            ->get();

        $posts = BlogPost::query()
            ->published()
            ->whereHas('galleryItems', fn ($query) => $query->active())
            ->latest('published_at')
            ->get();

        return view('pages.gallery', compact('items', 'events', 'posts', 'selectedArtForm', 'selectedRelatedType', 'selectedRelatedId'));
    }

    public function impact()
    {
        abort_unless(SiteSettings::current()->impactEnabled(), 404);

        $metrics = ImpactMetric::orderBy('sort_order')->get();
        $testimonials = Testimonial::query()
            ->active()
            ->orderByDesc('is_featured')
            ->latest()
            ->paginate(6);

        return view('pages.impact', compact('metrics', 'testimonials'));
    }

    public function blog()
    {
        abort_unless(SiteSettings::current()->blogEnabled(), 404);

        $posts = BlogPost::published()->latest('published_at')->paginate(9);

        return view('pages.blog', compact('posts'));
    }

    public function blogPost(string $slug)
    {
        abort_unless(SiteSettings::current()->blogEnabled(), 404);

        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->when($post->category, fn ($q) => $q->where('category', $post->category))
            ->latest('published_at')
            ->take(3)
            ->get();

        $galleryItems = $post->galleryItems()
            ->active()
            ->latest()
            ->take(4)
            ->get();

        return view('pages.blog-post', compact('post', 'relatedPosts', 'galleryItems'));
    }

    public function artistShow(Artist $artist)
    {
        $artist->load('disciplines');

        return view('pages.artist-show', compact('artist'));
    }

    public function artists(): View
    {
        $artists = Artist::query()->with('disciplines')->active()->ordered()->get();

        return view('pages.artists', compact('artists'));
    }

    public function pressKit(): View
    {
        return view('pages.press-kit');
    }

    public function donorWall(): View
    {
        abort_unless(SiteSettings::current()->donationsEnabled(), 404);

        $wallLedger = Donation::query()
            ->confirmedLedger()
            ->join('donors', 'donors.id', '=', 'donations.donor_id')
            ->whereNotNull('donations.donor_id')
            ->where('donations.is_anonymous', false)
            ->where('donations.public_recognition_consent', true)
            ->where('donors.public_recognition_consent', true);

        $donors = (clone $wallLedger)
            ->whereNotNull('donors.name')
            ->where('donors.name', '!=', '')
            ->selectRaw('donors.name as donor_name, SUM(donations.amount_cents) as total_amount_cents, MAX(donations.paid_at) as latest_donation')
            ->groupBy('donors.id', 'donors.name')
            ->havingRaw('SUM(donations.amount_cents) > 0')
            ->orderByDesc('total_amount_cents')
            ->get();

        $totalRaised = (int) ((clone $wallLedger)->sum('amount_cents') ?? 0) / 100;
        $totalDonors = $donors->count();

        return view('pages.donor-wall', compact('donors', 'totalRaised', 'totalDonors'));
    }
}
