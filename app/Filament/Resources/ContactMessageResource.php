<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Mail\ContactMessageReply;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Message Details')
                ->schema([
                    Forms\Components\TextInput::make('name')->disabled(),
                    Forms\Components\TextInput::make('email')->disabled(),
                    Forms\Components\TextInput::make('subject')->disabled(),
                    Forms\Components\Textarea::make('message')->disabled()->columnSpanFull()->rows(4),
                ]),
            Forms\Components\Section::make('Admin')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'new' => 'New',
                            'in_progress' => 'In Progress',
                            'resolved' => 'Resolved',
                            'replied' => 'Replied',
                        ])
                        ->default('new')
                        ->required(),
                    Forms\Components\Textarea::make('internal_notes')
                        ->rows(3)
                        ->columnSpanFull()
                        ->helperText('Internal notes — not visible to the sender.'),
                ]),
            Forms\Components\Section::make('Reply')
                ->schema([
                    Forms\Components\Textarea::make('reply')
                        ->disabled()
                        ->rows(4)
                        ->columnSpanFull()
                        ->visible(fn (ContactMessage $record) => $record->reply !== null),
                    Forms\Components\Placeholder::make('replied_at_display')
                        ->label('Replied at')
                        ->content(fn (ContactMessage $record) => $record->replied_at?->format('M j, Y g:i A') ?? '—')
                        ->visible(fn (ContactMessage $record) => $record->replied_at !== null),
                ])
                ->visible(fn (?ContactMessage $record) => $record?->reply !== null),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('subject')->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'danger',
                        'in_progress' => 'warning',
                        'resolved' => 'success',
                        'replied' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'in_progress' => 'In Progress',
                        'resolved' => 'Resolved',
                        'replied' => 'Replied',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('reply')
                    ->label('Reply')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn (ContactMessage $record) => ! $record->isReplied())
                    ->form([
                        Forms\Components\Placeholder::make('original_message')
                            ->label('Original Message')
                            ->content(fn (ContactMessage $record) => new HtmlString(
                                '<div style="padding: 12px; background: #f9fafb; border-radius: 6px; font-size: 13px; line-height: 1.6;">'
                                . '<strong>From:</strong> ' . e($record->name) . ' &lt;' . e($record->email) . '&gt;<br>'
                                . ($record->subject ? '<strong>Subject:</strong> ' . e($record->subject) . '<br>' : '')
                                . '<strong>Received:</strong> ' . $record->created_at->format('M j, Y g:i A') . '<br><br>'
                                . '<strong>Message:</strong><br>' . nl2br(e($record->message))
                                . '</div>'
                            )),
                        Forms\Components\Textarea::make('reply_message')
                            ->label('Your Reply')
                            ->required()
                            ->rows(6)
                            ->placeholder('Type your reply here...'),
                    ])
                    ->action(function (ContactMessage $record, array $data): void {
                        Mail::to($record->email)->send(
                            new ContactMessageReply($record, $data['reply_message'])
                        );

                        $record->update([
                            'status' => 'replied',
                            'reply' => $data['reply_message'],
                            'replied_at' => now(),
                            'is_read' => true,
                        ]);

                        Notification::make()
                            ->title('Reply sent')
                            ->body("Reply sent to {$record->email}")
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Reply to Contact Message')
                    ->modalSubmitActionLabel('Send Reply'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
