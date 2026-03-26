<?php

declare(strict_types=1);

namespace App\Filament\Resources\PerformanceRequestResource\Pages;

use App\Filament\Resources\PerformanceRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerformanceRequest extends EditRecord
{
    protected static string $resource = PerformanceRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
