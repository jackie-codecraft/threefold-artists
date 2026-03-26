<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\PerformanceRequest;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivityWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Recent Submissions';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PerformanceRequest::query()->latest()->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('organization_name')
                    ->label('Organization')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_name')
                    ->label('Contact'),
                Tables\Columns\TextColumn::make('preferred_art_form')
                    ->label('Art Form')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'reviewed' => 'info',
                        'scheduled' => 'success',
                        'completed' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5]);
    }
}
