<?php

declare(strict_types=1);

namespace App\Filament\Resources\PledgeResource\Pages;

use App\Filament\Resources\PledgeResource;
use Filament\Resources\Pages\ListRecords;

class ListPledges extends ListRecords
{
    protected static string $resource = PledgeResource::class;
}
