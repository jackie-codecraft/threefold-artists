<?php

declare(strict_types=1);

namespace App\Filament\Resources\ImpactMetricResource\Pages;

use App\Filament\Resources\ImpactMetricResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImpactMetric extends EditRecord
{
    protected static string $resource = ImpactMetricResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
