<?php

declare(strict_types=1);

namespace App\Filament\Resources\NewsletterResource\Pages;

use App\Filament\Resources\NewsletterResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNewsletter extends ViewRecord
{
    protected static string $resource = NewsletterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Preview Email')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn (): string => route('newsletters.preview', $this->record))
                ->openUrlInNewTab(),
        ];
    }
}
