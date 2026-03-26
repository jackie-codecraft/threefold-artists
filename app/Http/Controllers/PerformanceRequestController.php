<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PerformanceRequestFormRequest;
use App\Mail\PerformanceRequestSubmitted;
use App\Models\PerformanceRequest;
use Illuminate\Support\Facades\Mail;

class PerformanceRequestController extends Controller
{
    public function create()
    {
        return view('pages.request-performance');
    }

    public function store(PerformanceRequestFormRequest $request)
    {
        $performanceRequest = PerformanceRequest::create($request->validated());

        Mail::to(config('mail.from.address'))->send(
            new PerformanceRequestSubmitted($performanceRequest)
        );

        return redirect()->route('request-performance.thanks');
    }

    public function thanks()
    {
        return view('pages.request-performance-thanks');
    }
}
