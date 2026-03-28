<?php

declare(strict_types=1);

namespace App\Filament\Resources\PerformanceRequestResource\Pages;

use App\Filament\Resources\PerformanceRequestResource;
use App\Mail\PerformanceRequestReply;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class EditPerformanceRequest extends EditRecord
{
    protected static string $resource = PerformanceRequestResource::class;

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
                        ->label('Request Details')
                        ->content(fn (): HtmlString => new HtmlString(
                            '<div style="padding: 12px; background: #f9fafb; border-radius: 6px; font-size: 13px; line-height: 1.6;">'
                            . '<strong>Organization:</strong> ' . e($this->record->organization_name) . '<br>'
                            . '<strong>Contact:</strong> ' . e($this->record->contact_name) . ' &lt;' . e($this->record->contact_email) . '&gt;<br>'
                            . '<strong>Venue:</strong> ' . e($this->record->venue_type) . '<br>'
                            . ($this->record->preferred_art_form ? '<strong>Art Form:</strong> ' . e($this->record->preferred_art_form) . '<br>' : '')
                            . ($this->record->preferred_dates ? '<strong>Preferred Dates:</strong> ' . e($this->record->preferred_dates) . '<br>' : '')
                            . '</div>'
                        )),
                    Forms\Components\Textarea::make('reply_message')
                        ->label('Your Reply')
                        ->required()
                        ->rows(6)
                        ->placeholder('Type your reply here...'),
                ])
                ->action(function (array $data): void {
                    Mail::to($this->record->contact_email)->send(
                        new PerformanceRequestReply($this->record, $data['reply_message'])
                    );
                    $this->record->update([
                        'status' => 'replied',
                        'reply' => $data['reply_message'],
                        'replied_at' => now(),
                    ]);
                    Notification::make()
                        ->title('Reply sent')
                        ->body("Reply sent to {$this->record->contact_email}")
                        ->success()
                        ->send();
                    $this->refreshFormData(['status', 'reply', 'replied_at']);
                })
                ->modalHeading('Reply to Performance Request')
                ->modalSubmitActionLabel('Send Reply'),
            Actions\DeleteAction::make(),
        ];
    }
}
