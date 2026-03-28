<?php

declare(strict_types=1);

namespace App\Filament\Resources\NewsletterResource\Pages;

use App\Filament\Resources\NewsletterResource;
use App\Jobs\SendNewsletter;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditNewsletter extends EditRecord
{
    protected static string $resource = NewsletterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn (): string => route('newsletters.preview', $this->record))
                ->openUrlInNewTab(),
            Actions\Action::make('send')
                ->label('Send Newsletter')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->visible(fn (): bool => $this->record->isDraft())
                ->requiresConfirmation()
                ->modalHeading('Send Newsletter')
                ->modalDescription(fn (): string => "This will send \"{$this->record->subject}\" to {$this->record->getRecipientCount()} subscriber(s). This action cannot be undone.")
                ->modalSubmitActionLabel('Send Now')
                ->action(function (): void {
                    SendNewsletter::dispatch($this->record);
                    Notification::make()
                        ->title('Newsletter queued for sending')
                        ->body("Sending to {$this->record->getRecipientCount()} subscriber(s).")
                        ->success()
                        ->send();
                    $this->redirect(NewsletterResource::getUrl('index'));
                }),
            Actions\DeleteAction::make()
                ->visible(fn (): bool => $this->record->isDraft()),
        ];
    }
}
