<?php

declare(strict_types=1);

namespace App\Filament\Resources\TestimonialResource\Pages;

use App\Filament\Resources\TestimonialResource;
use App\Models\Testimonial;
use Filament\Actions;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTestimonials extends ListRecords
{
    protected static string $resource = TestimonialResource::class;

    public function getTabs(): array
    {
        return [
            'active' => Tab::make('Active')
                ->badge(Testimonial::query()->active()->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->active()),
            'featured' => Tab::make('Featured')
                ->badge(Testimonial::query()->featured()->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->featured()),
            'all' => Tab::make('All')
                ->badge(Testimonial::query()->count())
                ->badgeColor('gray'),
            'inactive' => Tab::make('Inactive')
                ->badge(Testimonial::query()->where('is_active', false)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('is_active', false)),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'all';
    }

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
