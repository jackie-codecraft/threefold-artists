<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Artist;
use App\Models\BlogPost;
use App\Models\Event;
use App\Models\GalleryItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentStatsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('Artists', (string) Artist::count())
                ->description(Artist::where('is_featured', true)->count() . ' featured'),
            Stat::make('Events', (string) Event::count())
                ->description(Event::upcoming()->count() . ' upcoming'),
            Stat::make('Blog Posts', (string) BlogPost::count())
                ->description(BlogPost::published()->count() . ' published'),
            Stat::make('Gallery Items', (string) GalleryItem::count())
                ->description(GalleryItem::where('is_featured', true)->count() . ' featured'),
        ];
    }
}
