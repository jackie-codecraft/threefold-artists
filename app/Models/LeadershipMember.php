<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class LeadershipMember extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'title',
        'biography',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $member): void {
            if ($member->sort_order !== null) {
                return;
            }

            $member->sort_order = ((int) static::query()->max('sort_order')) + 1;
        });
    }

    /** @param Builder<LeadershipMember> $query */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** @param Builder<LeadershipMember> $query */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('portrait')
            ->useDisk('public')
            ->singleFile();
    }
}
