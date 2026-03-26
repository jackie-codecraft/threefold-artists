<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Donation;
use App\Models\NewsletterSubscriber;
use App\Models\PerformanceRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalDonations = Donation::sum('amount');
        $monthlyDonations = Donation::where('created_at', '>=', now()->startOfMonth())->sum('amount');
        $totalDonors = Donation::whereNotNull('donor_email')->distinct('donor_email')->count();

        return [
            Stat::make('Total Donations', '$' . number_format((float) $totalDonations, 0))
                ->description('$' . number_format((float) $monthlyDonations, 0) . ' this month')
                ->color('success'),
            Stat::make('Donors', (string) $totalDonors)
                ->description(Donation::where('is_recurring', true)->count() . ' recurring')
                ->color('primary'),
            Stat::make('Performance Requests', (string) PerformanceRequest::count())
                ->description(PerformanceRequest::where('created_at', '>=', now()->startOfMonth())->count() . ' this month')
                ->color('warning'),
            Stat::make('Newsletter Subscribers', (string) NewsletterSubscriber::active()->count())
                ->description(NewsletterSubscriber::where('created_at', '>=', now()->startOfMonth())->count() . ' new this month')
                ->color('info'),
        ];
    }
}
