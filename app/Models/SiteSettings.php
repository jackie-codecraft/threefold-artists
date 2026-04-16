<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SiteSettings extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'show_blog',
        'show_impact',
        'show_gallery',
        'show_events',
        'contact_email',
        'donations_email',
    ];

    protected $casts = [
        'show_blog' => 'boolean',
        'show_impact' => 'boolean',
        'show_gallery' => 'boolean',
        'show_events' => 'boolean',
    ];

    public static function current(): self
    {
        if (! Schema::hasTable('site_settings')) {
            return static::defaults();
        }

        return static::query()->firstOrCreate([], static::defaultAttributes());
    }

    public static function defaults(): self
    {
        return tap(new static, function (self $settings): void {
            $settings->forceFill(static::defaultAttributes());
            $settings->exists = false;
        });
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultAttributes(): array
    {
        return [
            'show_blog' => true,
            'show_impact' => true,
            'show_gallery' => true,
            'show_events' => true,
            'contact_email' => 'hello@threefoldartists.org',
            'donations_email' => null,
        ];
    }

    public function blogEnabled(): bool
    {
        return $this->show_blog;
    }

    public function impactEnabled(): bool
    {
        return $this->show_impact;
    }

    public function galleryEnabled(): bool
    {
        return $this->show_gallery;
    }

    public function eventsEnabled(): bool
    {
        return $this->show_events;
    }
}
