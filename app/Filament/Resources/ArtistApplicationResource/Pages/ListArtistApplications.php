<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArtistApplicationResource\Pages;

use App\Filament\Resources\ArtistApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArtistApplications extends ListRecords
{
    protected static string $resource = ArtistApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
