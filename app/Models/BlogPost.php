<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BlogPost extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'author',
        'category',
        'published_at',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
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
    }

    public function featuredImageUrl(): ?string
    {
        $media = $this->getFirstMedia('featured_image');

        if ($media !== null) {
            $path = $media->getPathRelativeToRoot();

            if (Storage::disk($media->disk)->exists($path)) {
                return $media->getUrl();
            }

            $seededFallbackPath = 'blog/'.$media->file_name;

            if (Storage::disk($media->disk)->exists($seededFallbackPath)) {
                return Storage::disk($media->disk)->url($seededFallbackPath);
            }
        }

        if (filled($this->featured_image)) {
            $legacyPath = ltrim((string) parse_url($this->featured_image, PHP_URL_PATH), '/');

            if ($legacyPath !== '' && str_starts_with($legacyPath, 'storage/')) {
                $relativePath = substr($legacyPath, strlen('storage/'));

                if (Storage::disk('public')->exists($relativePath)) {
                    return Storage::disk('public')->url($relativePath);
                }

                $legacyFilename = basename($relativePath);
                $seededFallbackPath = 'blog/'.$legacyFilename;

                if ($legacyFilename !== '' && Storage::disk('public')->exists($seededFallbackPath)) {
                    return Storage::disk('public')->url($seededFallbackPath);
                }
            }

            return $this->featured_image;
        }

        return null;
    }
}
