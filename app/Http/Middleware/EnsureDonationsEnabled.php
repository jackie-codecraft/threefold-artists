<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\SiteSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDonationsEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(SiteSettings::current()->donationsEnabled(), 404);

        return $next($request);
    }
}
