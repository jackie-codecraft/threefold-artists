<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\BlogPost;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\ImpactMetric;
use App\Models\Testimonial;

class PageController extends Controller
{
    public function home()
    {
        $metrics = ImpactMetric::orderBy('sort_order')->get();
        $testimonials = Testimonial::featured()->latest()->take(3)->get();
        $upcomingEvents = Event::upcoming()->public()->take(3)->get();

        return view('pages.home', compact('metrics', 'testimonials', 'upcomingEvents'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function whatWeDo()
    {
        return view('pages.what-we-do');
    }

    public function events()
    {
        $events = Event::upcoming()->public()->paginate(12);

        return view('pages.events', compact('events'));
    }

    public function gallery()
    {
        $items = GalleryItem::latest()->paginate(12);

        return view('pages.gallery', compact('items'));
    }

    public function impact()
    {
        $metrics = ImpactMetric::orderBy('sort_order')->get();
        $testimonials = Testimonial::latest()->paginate(6);

        return view('pages.impact', compact('metrics', 'testimonials'));
    }

    public function blog()
    {
        $posts = BlogPost::published()->latest('published_at')->paginate(9);

        return view('pages.blog', compact('posts'));
    }

    public function blogPost(string $slug)
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        return view('pages.blog-post', compact('post'));
    }

    public function artists()
    {
        $artists = Artist::orderBy('name')->get();

        return view('pages.artists', compact('artists'));
    }
}
