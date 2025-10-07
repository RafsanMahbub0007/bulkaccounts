<?php

namespace App\Filament\Resources\GuideLineResource\Pages;

use App\Filament\Resources\GuideLineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGuideLines extends ListRecords
{
    protected static string $resource = GuideLineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
