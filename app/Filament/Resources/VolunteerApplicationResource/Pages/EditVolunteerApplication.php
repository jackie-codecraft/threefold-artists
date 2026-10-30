<?php

declare(strict_types=1);

namespace App\Filament\Resources\VolunteerApplicationResource\Pages;

use App\Filament\Resources\VolunteerApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVolunteerApplication extends EditRecord
{
    protected static string $resource = VolunteerApplicationResource::class;

    public function getTitle(): string
    {
        return $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('email')
                ->label('Email volunteer')
                ->icon('heroicon-o-envelope')
                ->url(fn (): string => 'mailto:'.$this->record->email),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Only follow-up fields are editable; submitted answers stay as the volunteer sent them.
        return array_intersect_key($data, array_flip(['status', 'internal_notes']));
    }
}
