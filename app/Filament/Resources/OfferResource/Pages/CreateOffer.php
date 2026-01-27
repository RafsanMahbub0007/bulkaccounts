<?php

namespace App\Filament\Resources\OfferResource\Pages;

use App\Filament\Resources\OfferResource;
use App\Jobs\SendOfferEmails;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateOffer extends CreateRecord
{
    protected static string $resource = OfferResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // Dispatch job to send emails to all users
        SendOfferEmails::dispatch($this->record);

        Notification::make()
            ->title('Offer created & emails are being sent to all users.')
            ->success()
            ->send();
    }
}
