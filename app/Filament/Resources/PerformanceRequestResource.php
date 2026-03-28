<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PerformanceRequestResource\Pages;
use App\Mail\PerformanceRequestReply;
use App\Models\PerformanceRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class PerformanceRequestResource extends Resource
{
    protected static ?string $model = PerformanceRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Organization')->schema([
                Forms\Components\TextInput::make('organization_name')->required(),
                Forms\Components\TextInput::make('venue_type')->required(),
                Forms\Components\Textarea::make('address'),
            ])->columns(2),
            Forms\Components\Section::make('Contact')->schema([
                Forms\Components\TextInput::make('contact_name')->required(),
                Forms\Components\TextInput::make('contact_email')->email()->required(),
                Forms\Components\TextInput::make('contact_phone'),
            ])->columns(3),
            Forms\Components\Section::make('Performance Details')->schema([
                Forms\Components\TextInput::make('audience_size')->numeric(),
                Forms\Components\TextInput::make('audience_demographics'),
                Forms\Components\TextInput::make('preferred_art_form'),
                Forms\Components\Textarea::make('preferred_dates'),
                Forms\Components\Textarea::make('accessibility_requirements'),
                Forms\Components\Textarea::make('notes')->columnSpanFull(),
            ])->columns(2),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'reviewed' => 'Reviewed',
                    'scheduled' => 'Scheduled',
                    'completed' => 'Completed',
                    'replied' => 'Replied',
                ])->required(),
            Forms\Components\Section::make('Admin')
                ->schema([
                    Forms\Components\Textarea::make('internal_notes')
                        ->rows(3)
                        ->columnSpanFull()
                        ->helperText('Internal notes — not visible to the requester.'),
                ]),
            Forms\Components\Section::make('Reply')
                ->schema([
                    Forms\Components\Textarea::make('reply')
                        ->disabled()
                        ->rows(4)
                        ->columnSpanFull()
                        ->visible(fn (PerformanceRequest $record): bool => $record->reply !== null),
                    Forms\Components\Placeholder::make('replied_at_display')
                        ->label('Replied at')
                        ->content(fn (PerformanceRequest $record): string => $record->replied_at?->format('M j, Y g:i A') ?? '—')
                        ->visible(fn (PerformanceRequest $record): bool => $record->replied_at !== null),
                ])
                ->visible(fn (?PerformanceRequest $record): bool => $record?->reply !== null),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('organization_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('contact_name')->searchable(),
                Tables\Columns\TextColumn::make('venue_type'),
                Tables\Columns\TextColumn::make('preferred_art_form'),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'reviewed' => 'info',
                        'scheduled' => 'success',
                        'completed' => 'gray',
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
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'replied' => 'Replied',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('reply')
                    ->label('Reply')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn (PerformanceRequest $record): bool => ! $record->isReplied())
                    ->form([
                        Forms\Components\Placeholder::make('original_request')
                            ->label('Request Details')
                            ->content(fn (PerformanceRequest $record): HtmlString => new HtmlString(
                                '<div style="padding: 12px; background: #f9fafb; border-radius: 6px; font-size: 13px; line-height: 1.6;">'
                                . '<strong>Organization:</strong> ' . e($record->organization_name) . '<br>'
                                . '<strong>Contact:</strong> ' . e($record->contact_name) . ' &lt;' . e($record->contact_email) . '&gt;<br>'
                                . '<strong>Venue Type:</strong> ' . e($record->venue_type) . '<br>'
                                . ($record->preferred_art_form ? '<strong>Preferred Art Form:</strong> ' . e(ucfirst($record->preferred_art_form)) . '<br>' : '')
                                . ($record->audience_size ? '<strong>Audience Size:</strong> ' . e((string) $record->audience_size) . '<br>' : '')
                                . ($record->preferred_dates ? '<strong>Preferred Dates:</strong> ' . e($record->preferred_dates) . '<br>' : '')
                                . '<strong>Submitted:</strong> ' . $record->created_at->format('M j, Y g:i A')
                                . '</div>'
                            )),
                        Forms\Components\Textarea::make('reply_message')
                            ->label('Your Reply')
                            ->required()
                            ->rows(6)
                            ->placeholder('Type your reply here...'),
                    ])
                    ->action(function (PerformanceRequest $record, array $data): void {
                        Mail::to($record->contact_email)->send(
                            new PerformanceRequestReply($record, $data['reply_message'])
                        );

                        $record->update([
                            'status' => 'replied',
                            'reply' => $data['reply_message'],
                            'replied_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Reply sent')
                            ->body("Reply sent to {$record->contact_email}")
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Reply to Performance Request')
                    ->modalSubmitActionLabel('Send Reply'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPerformanceRequests::route('/'),
            'create' => Pages\CreatePerformanceRequest::route('/create'),
            'edit' => Pages\EditPerformanceRequest::route('/{record}/edit'),
        ];
    }
}
