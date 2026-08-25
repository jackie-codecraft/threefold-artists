<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\LeadershipMemberResource\Pages;
use App\Models\LeadershipMember;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class LeadershipMemberResource extends Resource
{
    protected static ?string $model = LeadershipMember::class;

    protected static ?string $navigationLabel = 'Leadership';

    protected static ?string $pluralModelLabel = 'Leadership';

    protected static ?string $modelLabel = 'Leadership Member';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'People';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\SpatieMediaLibraryFileUpload::make('portrait')
                ->label('Portrait')
                ->helperText('Used in the Leadership section on the About page.')
                ->collection('portrait')
                ->disk('public')
                ->image()
                ->imageEditor()
                ->imageCropAspectRatio('1:1')
                ->required()
                ->columnSpanFull(),
            Forms\Components\RichEditor::make('biography')
                ->required()
                ->columnSpanFull(),
            Forms\Components\TextInput::make('sort_order')
                ->label('Display order')
                ->helperText('Lower numbers appear first on the About page.')
                ->numeric()
                ->minValue(0),
            Forms\Components\Toggle::make('is_active')
                ->label('Visible on the About page')
                ->default(true)
                ->helperText('Turn this off to hide a former or temporary member without deleting their record.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('portrait')
                    ->label('Portrait')
                    ->getStateUsing(fn (LeadershipMember $record): ?string => $record->getFirstMediaUrl('portrait'))
                    ->circular(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Visible')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Visibility'),
            ])
            ->recordActions([Actions\EditAction::make()])
            ->toolbarActions([Actions\BulkActionGroup::make([
                Actions\DeleteBulkAction::make(),
            ])])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->reorderRecordsTriggerAction(fn (Actions\Action $action) => $action
                ->button()
                ->label('Reorder leadership'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeadershipMembers::route('/'),
            'create' => Pages\CreateLeadershipMember::route('/create'),
            'edit' => Pages\EditLeadershipMember::route('/{record}/edit'),
        ];
    }
}
