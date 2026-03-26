<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ImpactMetricResource\Pages;
use App\Models\ImpactMetric;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ImpactMetricResource extends Resource
{
    protected static ?string $model = ImpactMetric::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('label')->required(),
            Forms\Components\TextInput::make('value')->required(),
            Forms\Components\TextInput::make('icon')->helperText('Use an emoji like 🎭 or 🎵'),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon'),
                Tables\Columns\TextColumn::make('label')->searchable(),
                Tables\Columns\TextColumn::make('value'),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImpactMetrics::route('/'),
            'create' => Pages\CreateImpactMetric::route('/create'),
            'edit' => Pages\EditImpactMetric::route('/{record}/edit'),
        ];
    }
}
