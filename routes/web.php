<?php

declare(strict_types=1);

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\GetInvolvedController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PerformanceRequestController;
use Illuminate\Support\Facades\Route;

// Main pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/what-we-do', [PageController::class, 'whatWeDo'])->name('what-we-do');
Route::get('/events', [PageController::class, 'events'])->name('events');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/impact', [PageController::class, 'impact'])->name('impact');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PageController::class, 'blogPost'])->name('blog.post');
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

// Donate
Route::get('/donate', [DonateController::class, 'show'])->name('donate');
Route::post('/donate/checkout', [DonateController::class, 'checkout'])->name('donate.checkout');
Route::get('/donate/success', [DonateController::class, 'success'])->name('donate.success');
Route::get('/donate/thank-you', [DonateController::class, 'thanks'])->name('donate.thanks');
