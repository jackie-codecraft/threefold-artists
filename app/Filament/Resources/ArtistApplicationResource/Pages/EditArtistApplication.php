<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArtistApplicationResource\Pages;

use App\Filament\Resources\ArtistApplicationResource;
use App\Mail\ArtistApplicationReply;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class EditArtistApplication extends EditRecord
{
    protected static string $resource = ArtistApplicationResource::class;

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
                        ->label('Application Details')
                        ->content(fn (): HtmlString => new HtmlString(
                            '<div style="padding: 12px; background: #f9fafb; border-radius: 6px; font-size: 13px; line-height: 1.6;">'
                            . '<strong>Name:</strong> ' . e($this->record->name) . '<br>'
                            . '<strong>Email:</strong> ' . e($this->record->email) . '<br>'
                            . '<strong>Discipline:</strong> ' . e(ucfirst($this->record->discipline)) . '<br>'
                            . ($this->record->experience ? '<strong>Experience:</strong> ' . e($this->record->experience) . '<br>' : '')
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
                        new ArtistApplicationReply($this->record, $data['reply_message'])
                    );
                    $this->record->update([
                        'status' => 'replied',
                        'reply' => $data['reply_message'],
                        'replied_at' => now(),
                    ]);
                    Notification::make()
                        ->title('Reply sent')
                        ->body("Reply sent to {$this->record->email}")
                        ->success()
                        ->send();
                    $this->refreshFormData(['status', 'reply', 'replied_at']);
                })
                ->modalHeading('Reply to Artist Application')
                ->modalSubmitActionLabel('Send Reply'),
            Actions\DeleteAction::make(),
        ];
    }
}
