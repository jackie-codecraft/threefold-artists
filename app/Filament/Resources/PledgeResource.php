<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PledgeResource\Pages;
use App\Models\Pledge;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PledgeResource extends Resource
{
    protected static ?string $model = Pledge::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Founding supporter pledge';

    protected static ?string $pluralModelLabel = 'Founding supporter pledges';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pledge details')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('phone')
                                ->tel()
                                ->maxLength(50),
                            Forms\Components\TextInput::make('amount')
                                ->numeric()
                                ->prefix('$')
                                ->required(),
                        ]),
                    Forms\Components\Textarea::make('message')
                        ->rows(5)
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('public_acknowledgment_consent')
                        ->label('May publicly recognize by name')
                        ->helperText('Turn this off when the supporter asked to remain anonymous.')
                        ->default(true),
                ]),
            Section::make('Admin')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'new' => 'New',
                            'contacted' => 'Contacted',
                            'converted' => 'Converted',
                            'closed' => 'Closed',
                        ])
                        ->default('new')
                        ->required(),
                    Forms\Components\Textarea::make('internal_notes')
                        ->rows(4)
                        ->columnSpanFull()
                        ->helperText('Internal notes — not visible to the supporter.'),
                    Forms\Components\DateTimePicker::make('acknowledged_at')
                        ->disabled(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable()->placeholder('—'),
                Tables\Columns\TextColumn::make('amount')->money('USD')->sortable(),
                Tables\Columns\IconColumn::make('public_acknowledgment_consent')
                    ->label('Public thank-you')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'danger',
                        'contacted' => 'warning',
                        'converted' => 'success',
                        'closed' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'converted' => 'Converted',
                        'closed' => 'Closed',
                    ]),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPledges::route('/'),
            'edit' => Pages\EditPledge::route('/{record}/edit'),
        ];
    }
}
