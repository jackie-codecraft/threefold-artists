<?php

declare(strict_types=1);

namespace App\Filament\Resources\VolunteerApplicationResource\Pages;

use App\Filament\Resources\VolunteerApplicationResource;
use Filament\Resources\Pages\ListRecords;

class ListVolunteerApplications extends ListRecords
{
    protected static string $resource = VolunteerApplicationResource::class;
}
