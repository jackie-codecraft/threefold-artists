<?php

declare(strict_types=1);

namespace App\Filament\Resources\PerformanceRequestResource\Pages;

use App\Filament\Resources\PerformanceRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerformanceRequests extends ListRecords
{
    protected static string $resource = PerformanceRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
