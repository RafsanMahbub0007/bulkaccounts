<?php

namespace App\Filament\Resources\GuideLineResource\Pages;

use App\Filament\Resources\GuideLineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGuideLine extends EditRecord
{
    protected static string $resource = GuideLineResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
