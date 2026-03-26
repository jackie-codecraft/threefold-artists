<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ArtistApplicationResource\Pages;
use App\Models\ArtistApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArtistApplicationResource extends Resource
{
    protected static ?string $model = ArtistApplication::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'People';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('email')->email()->required(),
            Forms\Components\TextInput::make('phone'),
            Forms\Components\TextInput::make('discipline')->required(),
            Forms\Components\Textarea::make('experience')->columnSpanFull(),
            Forms\Components\Textarea::make('bio')->columnSpanFull(),
            Forms\Components\Textarea::make('availability'),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'reviewed' => 'Reviewed',
                    'accepted' => 'Accepted',
                    'declined' => 'Declined',
                ])->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('discipline')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'reviewed' => 'info',
                        'accepted' => 'success',
                        'declined' => 'danger',
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
            'index' => Pages\ListArtistApplications::route('/'),
            'create' => Pages\CreateArtistApplication::route('/create'),
            'edit' => Pages\EditArtistApplication::route('/{record}/edit'),
        ];
    }
}
