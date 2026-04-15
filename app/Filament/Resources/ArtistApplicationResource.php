<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ArtistApplicationResource\Pages;
use App\Mail\ArtistApplicationReply;
use App\Models\Artist;
use App\Models\ArtistApplication;
use App\Models\Discipline;
use App\Support\DisciplineOptions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ArtistApplicationResource extends Resource
{
    protected static ?string $model = ArtistApplication::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'People';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Application Details')
                ->schema([
                    Forms\Components\TextInput::make('name')->required(),
                    Forms\Components\TextInput::make('email')->email()->required(),
                    Forms\Components\TextInput::make('phone'),
                    Forms\Components\Select::make('discipline')->options(DisciplineOptions::labels())->required(),
                    Forms\Components\Textarea::make('experience')->columnSpanFull(),
                    Forms\Components\Textarea::make('bio')->columnSpanFull(),
                    Forms\Components\Textarea::make('availability'),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'reviewed' => 'Reviewed',
                            'approved' => 'Approved',
                            'converted' => 'Converted',
                            'declined' => 'Declined',
                        ])->required(),
                ]),
            Forms\Components\Section::make('Conversion')
                ->schema([
                    Forms\Components\Placeholder::make('approved_at_display')
                        ->label('Approved at')
                        ->content(fn (ArtistApplication $record): string => $record->approved_at?->format('M j, Y g:i A') ?? '—'),
                    Forms\Components\Placeholder::make('converted_at_display')
                        ->label('Converted at')
                        ->content(fn (ArtistApplication $record): string => $record->converted_at?->format('M j, Y g:i A') ?? '—'),
                    Forms\Components\Placeholder::make('converted_artist_display')
                        ->label('Converted artist')
                        ->content(fn (ArtistApplication $record): string => $record->convertedArtist?->name ?? '—'),
                ])
                ->visible(fn (?ArtistApplication $record): bool => $record?->approved_at !== null || $record?->converted_at !== null || $record?->converted_artist_id !== null),
            Forms\Components\Section::make('Admin')
                ->schema([
                    Forms\Components\Textarea::make('internal_notes')
                        ->rows(3)
                        ->columnSpanFull()
                        ->helperText('Internal notes, not visible to the applicant.'),
                ]),
            Forms\Components\Section::make('Reply')
                ->schema([
                    Forms\Components\Textarea::make('reply')
                        ->disabled()
                        ->rows(4)
                        ->columnSpanFull()
                        ->visible(fn (ArtistApplication $record): bool => $record->reply !== null),
                    Forms\Components\Placeholder::make('replied_at_display')
                        ->label('Replied at')
                        ->content(fn (ArtistApplication $record): string => $record->replied_at?->format('M j, Y g:i A') ?? '—')
                        ->visible(fn (ArtistApplication $record): bool => $record->replied_at !== null),
                ])
                ->visible(fn (?ArtistApplication $record): bool => $record?->reply !== null),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('discipline')
                    ->formatStateUsing(fn (string $state): string => DisciplineOptions::label($state))
                    ->badge(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'reviewed' => 'info',
                        'approved' => 'success',
                        'converted' => 'primary',
                        'declined' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('convertedArtist.name')->label('Artist'),
                Tables\Columns\IconColumn::make('replied_at')->label('Replied')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'reviewed' => 'Reviewed',
                        'approved' => 'Approved',
                        'converted' => 'Converted',
                        'declined' => 'Declined',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (ArtistApplication $record): bool => ! $record->isApproved())
                    ->action(function (ArtistApplication $record): void {
                        $record->update([
                            'status' => 'approved',
                            'approved_at' => $record->approved_at ?? now(),
                        ]);

                        Notification::make()->title('Application approved')->success()->send();
                    }),
                Tables\Actions\Action::make('convert_to_artist')
                    ->label('Convert to artist')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('primary')
                    ->visible(fn (ArtistApplication $record): bool => ! $record->isConverted())
                    ->form([
                        Forms\Components\TextInput::make('name')->default(fn (ArtistApplication $record) => $record->name)->required(),
                        Forms\Components\TextInput::make('slug')
                            ->default(fn (ArtistApplication $record) => Str::slug($record->name))
                            ->required()
                            ->unique(Artist::class, 'slug'),
                        Forms\Components\Select::make('discipline_ids')
                            ->label('Disciplines')
                            ->options(fn (): array => Discipline::query()->orderBy('name')->pluck('name', 'id')->all())
                            ->default(fn (ArtistApplication $record): array => Discipline::query()->where('slug', $record->discipline)->pluck('id')->all())
                            ->multiple()
                            ->preload()
                            ->required(),
                        Forms\Components\Textarea::make('bio')
                            ->default(fn (ArtistApplication $record) => $record->bio)
                            ->rows(4),
                        Forms\Components\Toggle::make('is_featured')->default(false),
                    ])
                    ->action(function (ArtistApplication $record, array $data): void {
                        if ($record->isConverted()) {
                            Notification::make()->title('Application already converted')->warning()->send();
                            return;
                        }

                        $artist = Artist::create([
                            'name' => $data['name'],
                            'slug' => $data['slug'],
                            'bio' => $data['bio'] ?? null,
                            'is_featured' => (bool) ($data['is_featured'] ?? false),
                            'is_active' => false,
                        ]);

                        $artist->disciplines()->sync($data['discipline_ids']);

                        $record->update([
                            'status' => 'converted',
                            'approved_at' => $record->approved_at ?? now(),
                            'converted_at' => now(),
                            'converted_artist_id' => $artist->id,
                        ]);

                        Notification::make()
                            ->title('Artist created')
                            ->body('The new artist was created inactive by default.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('reply')
                    ->label('Reply')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->form([
                        Forms\Components\Placeholder::make('original_application')
                            ->label('Application Details')
                            ->content(fn (ArtistApplication $record): HtmlString => new HtmlString(
                                '<div style="padding: 12px; background: #f9fafb; border-radius: 6px; font-size: 13px; line-height: 1.6;">'
                                . '<strong>Name:</strong> ' . e($record->name) . '<br>'
                                . '<strong>Email:</strong> ' . e($record->email) . '<br>'
                                . '<strong>Discipline:</strong> ' . e($record->disciplineLabel()) . '<br>'
                                . ($record->experience ? '<strong>Experience:</strong> ' . e($record->experience) . '<br>' : '')
                                . ($record->availability ? '<strong>Availability:</strong> ' . e($record->availability) . '<br>' : '')
                                . '<strong>Submitted:</strong> ' . $record->created_at->format('M j, Y g:i A')
                                . '</div>'
                            )),
                        Forms\Components\Textarea::make('reply_message')
                            ->label('Your Reply')
                            ->required()
                            ->rows(6)
                            ->placeholder('Type your reply here...'),
                    ])
                    ->action(function (ArtistApplication $record, array $data): void {
                        Mail::to($record->email)->send(new ArtistApplicationReply($record, $data['reply_message']));

                        $record->update([
                            'reply' => $data['reply_message'],
                            'replied_at' => now(),
                        ]);

                        Notification::make()->title('Reply sent')->body("Reply sent to {$record->email}")->success()->send();
                    })
                    ->modalHeading('Reply to Artist Application')
                    ->modalSubmitActionLabel('Send Reply'),
                Tables\Actions\EditAction::make(),
            ])
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
