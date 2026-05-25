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
        'show_donations',
        'show_pledges',
        'contact_email',
        'donations_email',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'tiktok_url',
    ];

    protected $casts = [
        'show_blog' => 'boolean',
        'show_impact' => 'boolean',
        'show_gallery' => 'boolean',
        'show_events' => 'boolean',
        'show_donations' => 'boolean',
        'show_pledges' => 'boolean',
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
            'show_donations' => true,
            'show_pledges' => true,
            'contact_email' => 'hello@threefoldartists.org',
            'donations_email' => null,
            'facebook_url' => null,
            'instagram_url' => null,
            'youtube_url' => null,
            'tiktok_url' => null,
        ];
    }

    public function blogEnabled(): bool
    {
        return $this->flag('show_blog');
    }

    public function impactEnabled(): bool
    {
        return $this->flag('show_impact');
    }

    public function galleryEnabled(): bool
    {
        return $this->flag('show_gallery');
    }

    public function eventsEnabled(): bool
    {
        return $this->flag('show_events');
    }

    public function donationsEnabled(): bool
    {
        return $this->flag('show_donations');
    }

    public function pledgesEnabled(): bool
    {
        return $this->flag('show_pledges');
    }

    public function contactEmail(): string
    {
        $email = trim((string) $this->contact_email);

        if ($email !== '') {
            return $email;
        }

        return (string) static::defaultAttributes()['contact_email'];
    }

    /**
     * @return array<int, array{label: string, url: string}>
     */
    public function socialLinks(): array
    {
        return collect([
            'facebook_url' => 'Facebook',
            'instagram_url' => 'Instagram',
            'youtube_url' => 'YouTube',
            'tiktok_url' => 'TikTok',
        ])
            ->map(fn (string $label, string $attribute): array => [
                'label' => $label,
                'url' => trim((string) $this->getAttribute($attribute)),
            ])
            ->filter(fn (array $link): bool => $link['url'] !== '')
            ->values()
            ->all();
    }

    private function flag(string $attribute): bool
    {
        $value = $this->getAttribute($attribute);

        if ($value !== null) {
            return (bool) $value;
        }

        return (bool) (static::defaultAttributes()[$attribute] ?? false);
    }
}
