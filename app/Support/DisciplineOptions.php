<?php

declare(strict_types=1);

namespace App\Support;

class DisciplineOptions
{
    public const THEATRE = 'theatre';
    public const MUSIC = 'music';
    public const DANCE = 'dance';
    public const FINE_ARTS = 'fine_arts';

    public static function values(): array
    {
        return array_keys(static::labels());
    }

    public static function labels(): array
    {
        return [
            static::THEATRE => 'Theatre',
            static::MUSIC => 'Music',
            static::DANCE => 'Dance',
            static::FINE_ARTS => 'Fine Arts',
        ];
    }

    public static function label(string $value): string
    {
        return static::labels()[$value] ?? str($value)->replace('_', ' ')->title()->toString();
    }
}
