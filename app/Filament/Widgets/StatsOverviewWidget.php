<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Donation;
use App\Models\DonationSupport;
use App\Models\NewsletterSubscriber;
use App\Models\PerformanceRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $ledger = Donation::query()->confirmedLedger();
        $totalDonations = (int) ((clone $ledger)->sum('amount_cents') ?? 0) / 100;
        $monthlyDonations = (int) ((clone $ledger)
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount_cents') ?? 0) / 100;
        $totalDonors = (clone $ledger)
            ->where('status', 'paid')
            ->whereNotNull('donor_id')
            ->distinct('donor_id')
            ->count('donor_id');
        $activeSupports = DonationSupport::query()->where('status', 'active')->count();

        return [
            Stat::make('Net Paid Donations', '$'.number_format((float) $totalDonations, 0))
                ->description('$'.number_format((float) $monthlyDonations, 0).' net paid this month')
                ->color('success'),
            Stat::make('Paid Donors', (string) $totalDonors)
                ->description($activeSupports.' active recurring supports')
                ->color('primary'),
            Stat::make('Performance Requests', (string) PerformanceRequest::count())
                ->description(PerformanceRequest::where('created_at', '>=', now()->startOfMonth())->count().' this month')
                ->color('warning'),
            Stat::make('Newsletter Subscribers', (string) NewsletterSubscriber::active()->count())
                ->description(NewsletterSubscriber::where('created_at', '>=', now()->startOfMonth())->count().' new this month')
                ->color('info'),
        ];
    }
}
