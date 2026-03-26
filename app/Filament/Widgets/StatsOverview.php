<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\ArtistApplication;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\Event;
use App\Models\PerformanceRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Pending Requests', PerformanceRequest::where('status', 'pending')->count())
                ->description('Performance requests awaiting review')
                ->color('warning')
                ->icon('heroicon-o-ticket'),
            Stat::make('Upcoming Events', Event::where('date', '>=', now())->count())
                ->description('Scheduled performances')
                ->color('success')
                ->icon('heroicon-o-calendar-days'),
            Stat::make('Total Donations', '$' . number_format((float) Donation::sum('amount'), 2))
                ->description('All time')
                ->color('primary')
                ->icon('heroicon-o-currency-dollar'),
            Stat::make('New Applications', ArtistApplication::where('status', 'pending')->count())
                ->description('Artist applications pending review')
                ->color('info')
                ->icon('heroicon-o-user-plus'),
            Stat::make('Unread Messages', ContactMessage::where('is_read', false)->count())
                ->description('Contact messages')
                ->color('danger')
                ->icon('heroicon-o-envelope'),
        ];
    }
}
