<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'venue_name',
        'venue_address',
        'latitude',
        'longitude',
        'art_form',
        'featured_image',
        'is_public',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_public' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now())->orderBy('date');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function galleryItems(): MorphMany
    {
        return $this->morphMany(GalleryItem::class, 'galleryable');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
            ->useDisk('public')
            ->singleFile();

        $this->addMediaCollection('featured_thumbnail')
            ->useDisk('public')
            ->singleFile();
    }

    public function featuredThumbnailUrl(): ?string
    {
        return $this->getFirstMediaUrl('featured_thumbnail') ?: $this->featuredImageUrl();
    }

    public function featuredImageUrl(): ?string
    {
        $media = $this->getFirstMedia('featured_image');

        if ($media !== null) {
            $path = $media->getPathRelativeToRoot();

            if (Storage::disk($media->disk)->exists($path)) {
                return $media->getUrl();
            }
        }

        if (filled($this->featured_image)) {
            $legacyPath = ltrim((string) parse_url($this->featured_image, PHP_URL_PATH), '/');

            if ($legacyPath !== '' && str_starts_with($legacyPath, 'storage/')) {
                $relativePath = substr($legacyPath, strlen('storage/'));

                if (Storage::disk('public')->exists($relativePath)) {
                    return Storage::disk('public')->url($relativePath);
                }
            }

            return $this->featured_image;
        }

        return null;
    }
}
