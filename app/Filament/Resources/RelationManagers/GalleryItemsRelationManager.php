<?php

declare(strict_types=1);

namespace App\Filament\Resources\RelationManagers;

use App\Filament\Forms\Components\GalleryMediaUpload;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'galleryItems';

    protected static ?string $title = 'Gallery Images';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('title')
                ->maxLength(255),
            Forms\Components\Textarea::make('caption')
                ->rows(3)
                ->columnSpanFull(),
            Forms\Components\Select::make('type')
                ->options(['photo' => 'Photo', 'video' => 'Video'])
                ->default('photo')
                ->required(),
            Forms\Components\Select::make('art_form')
                ->options([
                    'theatre' => 'Theatre',
                    'music' => 'Music',
                    'dance' => 'Dance',
                    'fine_arts' => 'Fine Arts',
                ]),
            GalleryMediaUpload::make()
                ->columnSpanFull(),
            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true)
                ->live()
                ->afterStateUpdated(fn (bool $state, callable $set) => $state ?: $set('is_featured', false))
                ->helperText('Only active gallery items appear on the public site.'),
            Forms\Components\Toggle::make('is_featured')
                ->label('Featured')
                ->disabled(fn (callable $get): bool => ! (bool) $get('is_active'))
                ->helperText('Featured gallery items must also be active.'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('media')->collection('media'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('art_form')->label('Art Form')->badge(),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
                Tables\Columns\IconColumn::make('is_featured')->label('Featured')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
