<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\VolunteerApplicationResource\Pages;
use App\Models\VolunteerApplication;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VolunteerApplicationResource extends Resource
{
    protected static ?string $model = VolunteerApplication::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-hand-raised';

    protected static string|\UnitEnum|null $navigationGroup = 'People';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Volunteer Applications';

    public static function getNavigationBadge(): ?string
    {
        $count = VolunteerApplication::query()->where('status', 'new')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'New volunteer applications';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Submission')
                ->description('What the volunteer submitted on the website. Read-only.')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')->disabled(),
                    Forms\Components\TextInput::make('email')->disabled(),
                    Forms\Components\TextInput::make('phone')->disabled()->placeholder('Not provided'),
                    Forms\Components\Placeholder::make('submitted_at')
                        ->label('Submitted')
                        ->content(fn (VolunteerApplication $record): string => $record->created_at?->format('M j, Y g:i A') ?? '—'),
                    Forms\Components\CheckboxList::make('interests')
                        ->label('How they would like to help')
                        ->options(VolunteerApplication::INTERESTS)
                        ->columns(2)
                        ->disabled(),
                    Forms\Components\CheckboxList::make('availability')
                        ->label('General availability')
                        ->options(VolunteerApplication::AVAILABILITY)
                        ->disabled(),
                    Forms\Components\Textarea::make('experience')
                        ->label('Relevant experience')
                        ->disabled()
                        ->rows(3)
                        ->placeholder('Not provided')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('message')
                        ->label('Anything else')
                        ->disabled()
                        ->rows(3)
                        ->placeholder('Not provided')
                        ->columnSpanFull(),
                    Forms\Components\Placeholder::make('consent')
                        ->label('Consent to be contacted')
                        ->content(fn (VolunteerApplication $record): string => 'Agreed on '.($record->contact_consent_at?->format('M j, Y g:i A') ?? '—'))
                        ->columnSpanFull(),
                ]),
            Section::make('Follow-up')
                ->description('Internal only. Volunteers never see this.')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options(VolunteerApplication::STATUSES)
                        ->default('new')
                        ->required()
                        ->native(false),
                    Forms\Components\Textarea::make('internal_notes')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (VolunteerApplication $record): string => $record->email),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('interests')
                    ->label('Interests')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => VolunteerApplication::INTERESTS[$state] ?? $state)
                    ->limitList(3)
                    ->expandableLimitedList(),
                Tables\Columns\TextColumn::make('availability')
                    ->label('Availability')
                    ->formatStateUsing(fn (string $state): string => VolunteerApplication::AVAILABILITY[$state] ?? $state)
                    ->listWithLineBreaks()
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => VolunteerApplication::STATUSES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'danger',
                        'contacted' => 'warning',
                        'active' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->date('M j, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(VolunteerApplication::STATUSES),
                Tables\Filters\SelectFilter::make('interests')
                    ->label('Interest')
                    ->options(VolunteerApplication::INTERESTS)
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->whereJsonContains('interests', $data['value'])
                        : $query),
                Tables\Filters\SelectFilter::make('availability')
                    ->options(VolunteerApplication::AVAILABILITY)
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->whereJsonContains('availability', $data['value'])
                        : $query),
            ])
            ->recordActions([
                Actions\EditAction::make()->label('Open'),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVolunteerApplications::route('/'),
            'edit' => Pages\EditVolunteerApplication::route('/{record}/edit'),
        ];
    }
}
