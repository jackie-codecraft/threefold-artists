<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\Textarea::make('description')->columnSpanFull(),
            Forms\Components\DatePicker::make('date')->required(),
            Forms\Components\TimePicker::make('time'),
            Forms\Components\TextInput::make('venue_name')->required(),
            Forms\Components\Textarea::make('venue_address'),
            Forms\Components\Select::make('art_form')
                ->options([
                    'theatre' => 'Theatre',
                    'music' => 'Music',
                    'dance' => 'Dance',
                    'fine_arts' => 'Fine Arts',
                ]),
            Forms\Components\Toggle::make('is_public')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('date')->date()->sortable(),
                Tables\Columns\TextColumn::make('time')->time('g:i A'),
                Tables\Columns\TextColumn::make('venue_name')->searchable(),
                Tables\Columns\TextColumn::make('art_form')->badge(),
                Tables\Columns\IconColumn::make('is_public')->boolean(),
            ])
            ->defaultSort('date', 'asc')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
