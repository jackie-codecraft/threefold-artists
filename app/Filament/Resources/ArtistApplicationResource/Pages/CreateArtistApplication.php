<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArtistApplicationResource\Pages;

use App\Filament\Resources\ArtistApplicationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArtistApplication extends CreateRecord
{
    protected static string $resource = ArtistApplicationResource::class;
}
