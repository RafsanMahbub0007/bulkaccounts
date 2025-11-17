<?php

namespace App\Filament\Resources\ProductFeatureResource\Pages;

use App\Filament\Resources\ProductFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductFeature extends EditRecord
{
    protected static string $resource = ProductFeatureResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
