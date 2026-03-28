<?php

declare(strict_types=1);

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ArtistApplicationReplyController;
use App\Http\Controllers\ContactMessageReplyController;
use App\Http\Controllers\PerformanceRequestReplyController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\GetInvolvedController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NewsletterPreviewController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UnsubscribeController;
use App\Http\Controllers\PerformanceRequestController;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

// Sitemap
Route::get('/sitemap.xml', function () {
    $sitemap = Sitemap::create()
        ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency('weekly'))
        ->add(Url::create('/about')->setPriority(0.8))
        ->add(Url::create('/what-we-do')->setPriority(0.8))
        ->add(Url::create('/artists')->setPriority(0.7))
        ->add(Url::create('/events')->setPriority(0.7)->setChangeFrequency('weekly'))
        ->add(Url::create('/gallery')->setPriority(0.6))
        ->add(Url::create('/impact')->setPriority(0.6))
        ->add(Url::create('/blog')->setPriority(0.7)->setChangeFrequency('weekly'))
        ->add(Url::create('/request-a-performance')->setPriority(0.9))
        ->add(Url::create('/get-involved')->setPriority(0.8))
        ->add(Url::create('/donate')->setPriority(0.9))
        ->add(Url::create('/donor-wall')->setPriority(0.5))
        ->add(Url::create('/contact')->setPriority(0.7))
        ->add(Url::create('/press-kit')->setPriority(0.5));

    // Add blog posts
    \App\Models\BlogPost::published()->each(function (\App\Models\BlogPost $post) use ($sitemap): void {
        $sitemap->add(
            Url::create("/blog/{$post->slug}")
                ->setLastModificationDate($post->updated_at)
                ->setPriority(0.6)
        );
    });

    // Add artists with slugs
    \App\Models\Artist::all()->each(function (\App\Models\Artist $artist) use ($sitemap): void {
        if ($artist->slug) {
            $sitemap->add(
                Url::create("/artists/{$artist->slug}")
                    ->setPriority(0.5)
            );
        }
    });

    return $sitemap->toResponse(request());
})->name('sitemap');

// Main pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/what-we-do', [PageController::class, 'whatWeDo'])->name('what-we-do');
Route::get('/events', [PageController::class, 'events'])->name('events');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/impact', [PageController::class, 'impact'])->name('impact');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PageController::class, 'blogPost'])->name('blog.post');
Route::get('/artists/{artist:slug}', [PageController::class, 'artistShow'])->name('artists.show');
Route::get('/artists', [PageController::class, 'artists'])->name('artists');

// Performance Request
Route::get('/request-a-performance', [PerformanceRequestController::class, 'create'])->name('request-performance');
Route::post('/request-a-performance', [PerformanceRequestController::class, 'store'])->name('request-performance.store');
Route::get('/request-a-performance/thank-you', [PerformanceRequestController::class, 'thanks'])->name('request-performance.thanks');

// Get Involved
Route::get('/get-involved', [GetInvolvedController::class, 'create'])->name('get-involved');
Route::post('/get-involved', [GetInvolvedController::class, 'store'])->name('get-involved.store');
Route::get('/get-involved/thank-you', [GetInvolvedController::class, 'thanks'])->name('get-involved.thanks');

// Contact
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/contact/thank-you', [ContactController::class, 'thanks'])->name('contact.thanks');

// Donor Wall
Route::get('/donor-wall', [PageController::class, 'donorWall'])->name('donor-wall');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');

// Unsubscribe
Route::get('/unsubscribe', [UnsubscribeController::class, 'confirm'])->name('unsubscribe.confirm');
Route::post('/unsubscribe', [UnsubscribeController::class, 'process'])->name('unsubscribe.process');

// Newsletter Preview (admin only)
Route::get('/admin/newsletters/{newsletter}/preview', [NewsletterPreviewController::class, 'show'])
    ->name('newsletters.preview')
    ->middleware('auth');

// Press Kit
Route::get('/press-kit', [PageController::class, 'pressKit'])->name('press-kit');

// Contact Message Reply (signed URL, no auth required)
Route::get('/admin/contact-reply/{contactMessage}', [ContactMessageReplyController::class, 'show'])
    ->name('contact-message.reply')
    ->middleware('signed');
Route::post('/admin/contact-reply/{contactMessage}', [ContactMessageReplyController::class, 'send'])
    ->name('contact-message.reply.send')
    ->middleware('signed');

// Artist Application Reply (signed URL, no auth required)
Route::get('/admin/application-reply/{artistApplication}', [ArtistApplicationReplyController::class, 'show'])
    ->name('artist-application.reply')
    ->middleware('signed');
Route::post('/admin/application-reply/{artistApplication}', [ArtistApplicationReplyController::class, 'send'])
    ->name('artist-application.reply.send')
    ->middleware('signed');

// Performance Request Reply (signed URL, no auth required)
Route::get('/admin/performance-reply/{performanceRequest}', [PerformanceRequestReplyController::class, 'show'])
    ->name('performance-request.reply')
    ->middleware('signed');
Route::post('/admin/performance-reply/{performanceRequest}', [PerformanceRequestReplyController::class, 'send'])
    ->name('performance-request.reply.send')
    ->middleware('signed');

// Donate
Route::get('/donate', [DonateController::class, 'show'])->name('donate');
Route::post('/donate/checkout', [DonateController::class, 'checkout'])->name('donate.checkout');
Route::get('/donate/success', [DonateController::class, 'success'])->name('donate.success');
Route::get('/donate/thank-you', [DonateController::class, 'thanks'])->name('donate.thanks');
