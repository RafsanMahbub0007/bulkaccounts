<?php

namespace App\Filament\Resources\ProductFeatureResource\Pages;

use App\Filament\Resources\ProductFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductFeature extends CreateRecord
{
    protected static string $resource = ProductFeatureResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
