<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArtistApplicationResource\Pages;

use App\Filament\Resources\ArtistApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArtistApplication extends EditRecord
{
    protected static string $resource = ArtistApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
