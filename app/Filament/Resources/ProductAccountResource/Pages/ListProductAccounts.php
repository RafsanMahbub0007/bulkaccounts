<?php

namespace App\Filament\Resources\ProductAccountResource\Pages;

use App\Filament\Resources\ProductAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProductAccounts extends ListRecords
{
    protected static string $resource = ProductAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }


public function getTabs(): array
{
    return [
        'Un-sold' => Tab::make()
            ->badgeColor('success') // GREEN
            ->icon('heroicon-o-check-circle')
            ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('status', 'unsold')
            ),

        'Sold' => Tab::make()
            ->badgeColor('warning') // YELLOW
            ->icon('heroicon-o-currency-dollar')
            ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('status', 'sold')
            ),

        'Banned' => Tab::make()
            ->badgeColor('danger') // RED
            ->icon('heroicon-o-x-circle')
            ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('status', 'banned')
            ),
    ];
}

}
