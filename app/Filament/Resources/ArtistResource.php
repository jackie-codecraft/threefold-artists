<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ArtistResource\Pages;
use App\Models\Artist;
use App\Models\Discipline;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ArtistResource extends Resource
{
    protected static ?string $model = Artist::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static string|\UnitEnum|null $navigationGroup = 'People';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug((string) $state))),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\Select::make('disciplines')
                ->relationship('disciplines', 'name', fn (Builder $query) => $query->orderBy('name'))
                ->multiple()
                ->preload()
                ->searchable()
                ->required(),
            Forms\Components\Textarea::make('bio')->columnSpanFull(),
            Forms\Components\SpatieMediaLibraryFileUpload::make('photo')
                ->collection('photo')
                ->disk('public')
                ->image(),
            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(false)
                ->live()
                ->afterStateUpdated(fn (bool $state, callable $set) => $state ?: $set('is_featured', false))
                ->helperText('Only active artists appear on the public site and sitemap.'),
            Forms\Components\Toggle::make('is_featured')
                ->disabled(fn (callable $get): bool => ! (bool) $get('is_active'))
                ->helperText('Featured artists must also be active.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
                Tables\Columns\TextColumn::make('disciplines.name')
                    ->label('Disciplines')
                    ->badge()
                    ->separator(', '),
                Tables\Columns\IconColumn::make('is_featured')->label('Featured')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('discipline')
                    ->relationship('disciplines', 'name'),
            ])
            ->recordActions([\Filament\Actions\EditAction::make()])
            ->toolbarActions([\Filament\Actions\BulkActionGroup::make([\Filament\Actions\DeleteBulkAction::make()])])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->reorderRecordsTriggerAction(fn (\Filament\Actions\Action $action) => $action
                ->button()
                ->label('Reorder artists'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArtists::route('/'),
            'create' => Pages\CreateArtist::route('/create'),
            'edit' => Pages\EditArtist::route('/{record}/edit'),
        ];
    }
}
