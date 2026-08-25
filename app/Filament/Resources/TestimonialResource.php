<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static string|\UnitEnum|null $navigationGroup = 'Content';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Textarea::make('quote')->required()->rows(3)->columnSpanFull(),
            Forms\Components\TextInput::make('attribution')->required(),
            Forms\Components\TextInput::make('venue_name'),
            Forms\Components\Select::make('event_id')
                ->label('Related event')
                ->relationship('event', 'title')
                ->searchable()
                ->preload()
                ->helperText('Optional. Active testimonials linked here appear on that past event’s recap.'),
            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(false)
                ->live()
                ->afterStateUpdated(fn (bool $state, callable $set) => $state ?: $set('is_featured', false))
                ->helperText('Only active testimonials appear on the public site.'),
            Forms\Components\Toggle::make('is_featured')
                ->label('Featured')
                ->disabled(fn (callable $get): bool => ! (bool) $get('is_active'))
                ->helperText('Featured testimonials appear first on the Impact page and must also be active.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quote')->limit(60)->searchable(),
                Tables\Columns\TextColumn::make('attribution')->searchable(),
                Tables\Columns\TextColumn::make('venue_name'),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
                Tables\Columns\IconColumn::make('is_featured')->label('Featured')->boolean(),
            ])
            ->recordActions([\Filament\Actions\EditAction::make()])
            ->toolbarActions([\Filament\Actions\BulkActionGroup::make([\Filament\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
