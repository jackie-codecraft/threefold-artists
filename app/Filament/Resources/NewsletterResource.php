<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterResource\Pages;
use App\Models\Newsletter;
use App\Models\NewsletterSubscriber;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class NewsletterResource extends Resource
{
    protected static ?string $model = Newsletter::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-paper-airplane';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('subject')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
            Forms\Components\RichEditor::make('body')
                ->required()
                ->columnSpanFull()
                ->fileAttachmentsDisk('public')
                ->fileAttachmentsDirectory('newsletters'),
            Forms\Components\Radio::make('recipient_type')
                ->label('Send to')
                ->options([
                    'all' => 'All confirmed subscribers (' . NewsletterSubscriber::active()->confirmed()->count() . ')',
                    'custom' => 'Custom selection',
                ])
                ->default('all')
                ->live()
                ->required(),
            Forms\Components\Select::make('recipient_ids')
                ->label('Select recipients')
                ->multiple()
                ->options(NewsletterSubscriber::active()->confirmed()->pluck('email', 'id'))
                ->visible(fn (Get $get): bool => $get('recipient_type') === 'custom')
                ->required(fn (Get $get): bool => $get('recipient_type') === 'custom'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')->searchable()->sortable()->limit(50),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sending' => 'warning',
                        'sent' => 'success',
                    }),
                Tables\Columns\TextColumn::make('recipient_type')->badge(),
                Tables\Columns\TextColumn::make('total_sent')->label('Sent'),
                Tables\Columns\TextColumn::make('sent_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                \Filament\Actions\EditAction::make()
                    ->visible(fn (Newsletter $record): bool => $record->isDraft()),
                \Filament\Actions\ViewAction::make()
                    ->visible(fn (Newsletter $record): bool => ! $record->isDraft()),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletters::route('/'),
            'create' => Pages\CreateNewsletter::route('/create'),
            'edit' => Pages\EditNewsletter::route('/{record}/edit'),
            'view' => Pages\ViewNewsletter::route('/{record}'),
        ];
    }
}
