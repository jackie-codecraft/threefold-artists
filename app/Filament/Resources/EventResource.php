<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\RelationManagers\GalleryItemsRelationManager;
use App\Models\Event;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\Textarea::make('description')->columnSpanFull(),
            Forms\Components\Textarea::make('recap')
                ->label('Post-event recap')
                ->rows(6)
                ->helperText('Published only on a past event after you publish its recap.')
                ->columnSpanFull(),
            Forms\Components\DatePicker::make('date')->required(),
            Forms\Components\TimePicker::make('time'),
            Forms\Components\TextInput::make('venue_name')->required(),
            Forms\Components\Textarea::make('venue_address'),
            Forms\Components\SpatieMediaLibraryFileUpload::make('featured_image')
                ->label('Original featured image')
                ->helperText('This is the full image used on the event detail hero. It is never cropped.')
                ->collection('featured_image')
                ->disk('public')
                ->image()
                ->columnSpanFull(),
            Forms\Components\SpatieMediaLibraryFileUpload::make('featured_thumbnail')
                ->label('Event card thumbnail crop')
                ->helperText('Optional. Upload the same original image, then choose the 4:3 crop used on event cards. The full image above remains untouched.')
                ->collection('featured_thumbnail')
                ->disk('public')
                ->image()
                ->imageAspectRatio('4:3')
                ->automaticallyOpenImageEditorForAspectRatio()
                ->imageEditor()
                ->imageEditorMode(2)
                ->columnSpanFull(),
            Forms\Components\TextInput::make('latitude')->numeric()->step(0.0000001),
            Forms\Components\TextInput::make('longitude')->numeric()->step(0.0000001),
            Forms\Components\Select::make('art_form')
                ->options([
                    'theatre' => 'Theatre',
                    'music' => 'Music',
                    'dance' => 'Dance',
                    'fine_arts' => 'Fine Arts',
                ]),
            Forms\Components\Toggle::make('is_public')->default(true),
            Forms\Components\Toggle::make('is_past_published')
                ->label('Publish in Past Events archive')
                ->helperText('Requires a past event date. Draft recaps remain private until this is enabled.'),
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
                Tables\Columns\IconColumn::make('is_past_published')->label('In Archive')->boolean(),
            ])
            ->defaultSort('date', 'asc')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            GalleryItemsRelationManager::class,
        ];
    }
}
