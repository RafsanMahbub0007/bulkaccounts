<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('syncAll')
                ->label('Sync All Sheets')
                ->icon('heroicon-o-arrow-path-rounded-square')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    $products = Product::whereNotNull('google_sheet_id')
                        ->where('google_sheet_id', '!=', '')
                        ->get();

                    $success = 0;
                    $failed = 0;

                    foreach ($products as $product) {
                        try {
                            ProductResource::syncSheet($product, notify: false);
                            $success++;
                        } catch (\Throwable $e) {
                            $failed++;
                        }
                    }

                    if ($failed === 0) {
                        Notification::make()
                            ->title("Synced {$success} products successfully")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title("Sync completed: {$success} success, {$failed} failed")
                            ->warning()
                            ->send();
                    }
                }),
        ];
    }
}
