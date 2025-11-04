<?php

namespace App\Filament\Resources\TermsConditionsResource\Pages;

use App\Filament\Resources\TermsConditionsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTermsConditions extends ListRecords
{
    protected static string $resource = TermsConditionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
