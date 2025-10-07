<?php

namespace App\Filament\Resources\GuideLineResource\Pages;

use App\Filament\Resources\GuideLineResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGuideLine extends CreateRecord
{
    protected static string $resource = GuideLineResource::class;
        protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
