<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\PerformanceRequestReply;
use App\Models\PerformanceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class PerformanceRequestReplyController extends Controller
{
    public function show(Request $request, PerformanceRequest $performanceRequest): View
    {
        return view('performance-request.reply', [
            'performanceRequest' => $performanceRequest,
            'postUrl' => URL::signedRoute('performance-request.reply.send', ['performanceRequest' => $performanceRequest->id]),
        ]);
    }

    public function send(Request $request, PerformanceRequest $performanceRequest): RedirectResponse
    {
        $validated = $request->validate([
            'reply_message' => ['required', 'string', 'max:5000'],
        ]);

        Mail::to($performanceRequest->contact_email)->send(
            new PerformanceRequestReply($performanceRequest, $validated['reply_message'])
        );

        $performanceRequest->update([
            'status' => 'replied',
            'reply' => $validated['reply_message'],
            'replied_at' => now(),
        ]);

        return redirect(URL::signedRoute('performance-request.reply', ['performanceRequest' => $performanceRequest->id]))
            ->with('success', true);
    }
}
