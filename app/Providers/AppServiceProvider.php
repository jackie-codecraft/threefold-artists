<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\SiteSettings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('contact-form', function (Request $request) {
            return Limit::perHour(3)->by($request->ip());
        });

        RateLimiter::for('performance-request', function (Request $request) {
            return Limit::perHour(3)->by($request->ip());
        });

        RateLimiter::for('artist-application', function (Request $request) {
            return Limit::perHour(3)->by($request->ip());
        });

        RateLimiter::for('newsletter-subscription', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        RateLimiter::for('pledge-form', function (Request $request) {
            return Limit::perHour(10)->by($request->ip());
        });

        RateLimiter::for('donor-access-link', function (Request $request) {
            return Limit::perHour(5)->by($request->ip().'|'.mb_strtolower(trim((string) $request->input('email'))));
        });

        View::composer('*', function ($view): void {
            $view->with('siteSettings', SiteSettings::current());
        });
    }
}
