<?php

declare(strict_types=1);

namespace App\Filament\Resources\PerformanceRequestResource\Pages;

use App\Filament\Resources\PerformanceRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePerformanceRequest extends CreateRecord
{
    protected static string $resource = PerformanceRequestResource::class;
}
