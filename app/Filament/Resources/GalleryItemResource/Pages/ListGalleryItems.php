<?php

declare(strict_types=1);

namespace App\Filament\Resources\GalleryItemResource\Pages;

use App\Filament\Resources\GalleryItemResource;
use App\Models\GalleryItem;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListGalleryItems extends ListRecords
{
    protected static string $resource = GalleryItemResource::class;

    public function getTabs(): array
    {
        return [
            'active' => Tab::make('Active')
                ->badge(GalleryItem::query()->active()->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->active()),
            'featured' => Tab::make('Featured')
                ->badge(GalleryItem::query()->featured()->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->featured()),
            'all' => Tab::make('All')
                ->badge(GalleryItem::query()->count())
                ->badgeColor('gray'),
            'inactive' => Tab::make('Inactive')
                ->badge(GalleryItem::query()->where('is_active', false)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('is_active', false)),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'active';
    }

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
