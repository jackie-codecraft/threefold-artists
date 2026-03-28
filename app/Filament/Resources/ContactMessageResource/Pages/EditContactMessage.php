<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use App\Mail\ContactMessageReply;
use App\Models\ContactMessage;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class EditContactMessage extends EditRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reply')
                ->label('Reply')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->visible(fn (): bool => ! $this->record->isReplied())
                ->form([
                    Forms\Components\Placeholder::make('original')
                        ->label('Original Message')
                        ->content(fn (): HtmlString => new HtmlString(
                            '<div style="padding: 12px; background: #f9fafb; border-radius: 6px; font-size: 13px; line-height: 1.6;">'
                            . '<strong>From:</strong> ' . e($this->record->name) . ' &lt;' . e($this->record->email) . '&gt;<br>'
                            . ($this->record->subject ? '<strong>Subject:</strong> ' . e($this->record->subject) . '<br>' : '')
                            . '<strong>Message:</strong><br>' . nl2br(e($this->record->message))
                            . '</div>'
                        )),
                    Forms\Components\Textarea::make('reply_message')
                        ->label('Your Reply')
                        ->required()
                        ->rows(6)
                        ->placeholder('Type your reply here...'),
                ])
                ->action(function (array $data): void {
                    Mail::to($this->record->email)->send(
                        new ContactMessageReply($this->record, $data['reply_message'])
                    );
                    $this->record->update([
                        'status' => 'replied',
                        'reply' => $data['reply_message'],
                        'replied_at' => now(),
                        'is_read' => true,
                    ]);
                    Notification::make()
                        ->title('Reply sent')
                        ->body("Reply sent to {$this->record->email}")
                        ->success()
                        ->send();
                    $this->refreshFormData(['status', 'reply', 'replied_at', 'is_read']);
                })
                ->modalHeading('Reply to Contact Message')
                ->modalSubmitActionLabel('Send Reply'),
            Actions\DeleteAction::make(),
        ];
    }
}
