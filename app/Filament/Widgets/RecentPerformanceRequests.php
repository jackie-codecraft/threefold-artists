<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\PerformanceRequest;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentPerformanceRequests extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(PerformanceRequest::latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('organization_name'),
                Tables\Columns\TextColumn::make('contact_name'),
                Tables\Columns\TextColumn::make('venue_type'),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'reviewed' => 'info',
                        'scheduled' => 'success',
                        'completed' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->heading('Recent Performance Requests');
    }
}
