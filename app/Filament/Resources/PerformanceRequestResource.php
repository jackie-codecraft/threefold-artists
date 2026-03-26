<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PerformanceRequestResource\Pages;
use App\Models\PerformanceRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PerformanceRequestResource extends Resource
{
    protected static ?string $model = PerformanceRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Organization')->schema([
                Forms\Components\TextInput::make('organization_name')->required(),
                Forms\Components\TextInput::make('venue_type')->required(),
                Forms\Components\Textarea::make('address'),
            ])->columns(2),
            Forms\Components\Section::make('Contact')->schema([
                Forms\Components\TextInput::make('contact_name')->required(),
                Forms\Components\TextInput::make('contact_email')->email()->required(),
                Forms\Components\TextInput::make('contact_phone'),
            ])->columns(3),
            Forms\Components\Section::make('Performance Details')->schema([
                Forms\Components\TextInput::make('audience_size')->numeric(),
                Forms\Components\TextInput::make('audience_demographics'),
                Forms\Components\TextInput::make('preferred_art_form'),
                Forms\Components\Textarea::make('preferred_dates'),
                Forms\Components\Textarea::make('accessibility_requirements'),
                Forms\Components\Textarea::make('notes')->columnSpanFull(),
            ])->columns(2),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'reviewed' => 'Reviewed',
                    'scheduled' => 'Scheduled',
                    'completed' => 'Completed',
                ])->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('organization_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('contact_name')->searchable(),
                Tables\Columns\TextColumn::make('venue_type'),
                Tables\Columns\TextColumn::make('preferred_art_form'),
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
            ->defaultSort('created_at', 'desc')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPerformanceRequests::route('/'),
            'create' => Pages\CreatePerformanceRequest::route('/create'),
            'edit' => Pages\EditPerformanceRequest::route('/{record}/edit'),
        ];
    }
}
