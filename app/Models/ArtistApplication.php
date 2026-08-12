<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\DisciplineOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ArtistApplication extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'discipline',
        'experience',
        'bio',
        'availability',
        'status',
        'converted_artist_id',
        'approved_at',
        'converted_at',
        'reply',
        'replied_at',
        'internal_notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'converted_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    public function isReplied(): bool
    {
        return $this->replied_at !== null;
    }

    public function isApproved(): bool
    {
        return in_array($this->status, ['approved', 'converted'], true);
    }

    public function isConverted(): bool
    {
        return $this->status === 'converted' || $this->converted_at !== null || $this->converted_artist_id !== null;
    }

    public function convertedArtist(): BelongsTo
    {
        return $this->belongsTo(Artist::class, 'converted_artist_id');
    }

    public function disciplineLabel(): string
    {
        return DisciplineOptions::label($this->discipline);
    }

    public function mediaPreviewUrl(string $collection): ?string
    {
        return $this->signedMediaUrl($collection, 'artist-application.media.show');
    }

    public function mediaDownloadUrl(string $collection): ?string
    {
        return $this->signedMediaUrl($collection, 'artist-application.media.download');
    }

    public function supportingMediaPreviewUrl(Media $media): ?string
    {
        return $this->signedSupportingMediaUrl($media, 'artist-application.media.show');
    }

    public function supportingMediaDownloadUrl(Media $media): ?string
    {
        return $this->signedSupportingMediaUrl($media, 'artist-application.media.download');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->useDisk('local')
            ->singleFile();

        $this->addMediaCollection('resume')
            ->useDisk('local')
            ->singleFile();

        $this->addMediaCollection('supporting_media')
            ->useDisk('local');
    }

    private function signedMediaUrl(string $collection, string $route): ?string
    {
        $media = $this->getFirstMedia($collection);

        if ($media === null) {
            return null;
        }

        return URL::signedRoute($route, [
            'artistApplication' => $this,
            'collection' => $collection,
            'media' => $media,
        ]);
    }

    private function signedSupportingMediaUrl(Media $media, string $route): ?string
    {
        if ($media->model_type !== self::class
            || (int) $media->model_id !== $this->id
            || $media->collection_name !== 'supporting_media') {
            return null;
        }

        return URL::signedRoute($route, [
            'artistApplication' => $this,
            'collection' => 'supporting_media',
            'media' => $media,
        ]);
    }
}
