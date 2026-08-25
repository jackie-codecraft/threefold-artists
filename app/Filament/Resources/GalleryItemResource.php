<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Forms\Components\GalleryMediaUpload;
use App\Filament\Resources\GalleryItemResource\Pages;
use App\Models\BlogPost;
use App\Models\Event;
use App\Models\GalleryItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryItemResource extends Resource
{
    protected static ?string $model = GalleryItem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('title'),
            Forms\Components\Textarea::make('caption'),
            Forms\Components\Select::make('type')
                ->options(['photo' => 'Photo', 'video' => 'Video'])
                ->default('photo')
                ->live(),
            Forms\Components\Select::make('art_form')
                ->options([
                    'theatre' => 'Theatre',
                    'music' => 'Music',
                    'dance' => 'Dance',
                    'fine_arts' => 'Fine Arts',
                ]),
            Forms\Components\Select::make('galleryable_type')
                ->label('Related Content Type')
                ->options([
                    Event::class => 'Event',
                    BlogPost::class => 'Blog Post',
                ])
                ->live()
                ->afterStateUpdated(fn (callable $set) => $set('galleryable_id', null))
                ->helperText('Optional. Use this to connect the image to a specific event or blog post.'),
            Forms\Components\Select::make('galleryable_id')
                ->label('Related Content')
                ->options(function (callable $get): array {
                    return match ($get('galleryable_type')) {
                        Event::class => Event::query()->orderByDesc('date')->pluck('title', 'id')->all(),
                        BlogPost::class => BlogPost::query()->orderByDesc('published_at')->pluck('title', 'id')->all(),
                        default => [],
                    };
                })
                ->searchable()
                ->visible(fn (callable $get): bool => filled($get('galleryable_type'))),
            Forms\Components\TextInput::make('event_name'),
            GalleryMediaUpload::make(),
            GalleryMediaUpload::thumbnail()
                ->visible(fn (callable $get): bool => $get('type') === 'photo'),
            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(false)
                ->live()
                ->afterStateUpdated(fn (bool $state, callable $set) => $state ?: $set('is_featured', false))
                ->helperText('Only active gallery items appear on the public site.'),
            Forms\Components\Toggle::make('is_featured')
                ->label('Featured')
                ->disabled(fn (callable $get): bool => ! (bool) $get('is_active'))
                ->helperText('Featured gallery items must also be active.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('media')->collection('media'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('galleryable.title')
                    ->label('Related To')
                    ->placeholder('—')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
                Tables\Columns\IconColumn::make('is_featured')->label('Featured')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleryItems::route('/'),
            'create' => Pages\CreateGalleryItem::route('/create'),
            'edit' => Pages\EditGalleryItem::route('/{record}/edit'),
        ];
    }
}
