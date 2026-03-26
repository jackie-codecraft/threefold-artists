<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ArtistApplicationRequest;
use App\Mail\ArtistApplicationSubmitted;
use App\Models\ArtistApplication;
use Illuminate\Support\Facades\Mail;

class GetInvolvedController extends Controller
{
    public function create()
    {
        return view('pages.get-involved');
    }

    public function store(ArtistApplicationRequest $request)
    {
        $application = ArtistApplication::create($request->validated());

        Mail::to(config('mail.from.address'))->send(
            new ArtistApplicationSubmitted($application)
        );

        return redirect()->route('get-involved.thanks');
    }

    public function thanks()
    {
        return view('pages.get-involved-thanks');
    }
}
