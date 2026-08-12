<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ArtistApplication;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArtistApplicationMediaController extends Controller
{
    public function show(ArtistApplication $artistApplication, string $collection, Media $media): StreamedResponse
    {
        $this->authorizeMedia($artistApplication, $collection, $media);

        return Storage::disk($media->disk)->response(
            $media->getPathRelativeToRoot(),
            $media->file_name,
            ['Content-Type' => $media->mime_type],
            'inline'
        );
    }

    public function download(ArtistApplication $artistApplication, string $collection, Media $media): StreamedResponse
    {
        $this->authorizeMedia($artistApplication, $collection, $media);

        return Storage::disk($media->disk)->download(
            $media->getPathRelativeToRoot(),
            $media->file_name,
            ['Content-Type' => $media->mime_type]
        );
    }

    private function authorizeMedia(ArtistApplication $artistApplication, string $collection, Media $media): void
    {
        abort_unless(in_array($collection, ['photo', 'resume', 'supporting_media'], true), 404);
        abort_unless($media->model_type === ArtistApplication::class, 404);
        abort_unless((int) $media->model_id === $artistApplication->id, 404);
        abort_unless($media->collection_name === $collection, 404);
    }
}
