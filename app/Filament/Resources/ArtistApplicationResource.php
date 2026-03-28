<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ArtistApplicationResource\Pages;
use App\Mail\ArtistApplicationReply;
use App\Models\ArtistApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

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
                    Forms\Components\TextInput::make('discipline')->required(),
                    Forms\Components\Textarea::make('experience')->columnSpanFull(),
                    Forms\Components\Textarea::make('bio')->columnSpanFull(),
                    Forms\Components\Textarea::make('availability'),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'reviewed' => 'Reviewed',
                            'accepted' => 'Accepted',
                            'declined' => 'Declined',
                            'replied' => 'Replied',
                        ])->required(),
                ]),
            Forms\Components\Section::make('Admin')
                ->schema([
                    Forms\Components\Textarea::make('internal_notes')
                        ->rows(3)
                        ->columnSpanFull()
                        ->helperText('Internal notes — not visible to the applicant.'),
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
                Tables\Columns\TextColumn::make('discipline')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'reviewed' => 'info',
                        'accepted' => 'success',
                        'declined' => 'danger',
                        'replied' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'reviewed' => 'Reviewed',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                        'replied' => 'Replied',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('reply')
                    ->label('Reply')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn (ArtistApplication $record): bool => ! $record->isReplied())
                    ->form([
                        Forms\Components\Placeholder::make('original_application')
                            ->label('Application Details')
                            ->content(fn (ArtistApplication $record): HtmlString => new HtmlString(
                                '<div style="padding: 12px; background: #f9fafb; border-radius: 6px; font-size: 13px; line-height: 1.6;">'
                                . '<strong>Name:</strong> ' . e($record->name) . '<br>'
                                . '<strong>Email:</strong> ' . e($record->email) . '<br>'
                                . '<strong>Discipline:</strong> ' . e(ucfirst(str_replace('_', ' ', $record->discipline))) . '<br>'
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
                        Mail::to($record->email)->send(
                            new ArtistApplicationReply($record, $data['reply_message'])
                        );

                        $record->update([
                            'status' => 'replied',
                            'reply' => $data['reply_message'],
                            'replied_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Reply sent')
                            ->body("Reply sent to {$record->email}")
                            ->success()
                            ->send();
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
