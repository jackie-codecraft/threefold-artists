<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeadershipMemberResource\Pages;

use App\Filament\Resources\LeadershipMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeadershipMembers extends ListRecords
{
    protected static string $resource = LeadershipMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add leadership member'),
        ];
    }
}
