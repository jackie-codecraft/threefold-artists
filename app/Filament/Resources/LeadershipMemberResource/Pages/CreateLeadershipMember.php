<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeadershipMemberResource\Pages;

use App\Filament\Resources\LeadershipMemberResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeadershipMember extends CreateRecord
{
    protected static string $resource = LeadershipMemberResource::class;
}
