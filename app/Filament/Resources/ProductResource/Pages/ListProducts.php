<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource =
        ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [

            /*
             * Create Product
             */
            Actions\CreateAction::make(),

            /*
             * Sync ALL Product Sheets
             */
            Actions\Action::make('syncAll')
                ->label('Sync All Sheets')
                ->icon(
                    'heroicon-o-arrow-path-rounded-square'
                )
                ->color('warning')
                ->requiresConfirmation()

                ->modalHeading(
                    'Sync All Product Sheets'
                )

                ->modalDescription(
                    'This will synchronize all products that have a Google Sheet ID.'
                )

                ->action(function () {

                    /*
                     * Get products that have a Google
                     * Sheet configured.
                     */
                    $products = Product::whereNotNull(
                        'google_sheet_id'
                    )
                        ->where(
                            'google_sheet_id',
                            '!=',
                            ''
                        )
                        ->get();

                    /*
                     * Counters.
                     */
                    $success = 0;

                    $failed = 0;

                    /*
                     * Store errors.
                     */
                    $errors = [];

                    /*
                     * Nothing to sync.
                     */
                    if ($products->isEmpty()) {

                        Notification::make()
                            ->title(
                                'No products to sync'
                            )
                            ->body(
                                'No products have a Google Sheet ID configured.'
                            )
                            ->warning()
                            ->send();

                        return;
                    }

                    /*
                     * Sync every product.
                     */
                    foreach (
                        $products as $product
                    ) {

                        try {

                            /*
                             * Do NOT show an individual
                             * notification.
                             */
                            ProductResource::syncSheet(
                                $product,
                                notify: false
                            );

                            $success++;

                        } catch (\Throwable $e) {

                            $failed++;

                            /*
                             * Save detailed information.
                             */
                            $errors[] =
                                "• {$product->name}\n" .
                                "  Sheet ID: {$product->google_sheet_id}\n" .
                                "  Error: {$e->getMessage()}";
                        }
                    }

                    /*
                     * Everything succeeded.
                     */
                    if ($failed === 0) {

                        Notification::make()
                            ->title(
                                "Synced {$success} products successfully"
                            )
                            ->body(
                                'All configured Google Sheets were synchronized successfully.'
                            )
                            ->success()
                            ->send();

                        return;
                    }

                    /*
                     * Some failed.
                     */
                    $errorText = implode(
                        "\n\n",
                        $errors
                    );

                    Notification::make()
                        ->title(
                            "Sync completed: {$success} success, {$failed} failed"
                        )
                        ->body($errorText)
                        ->warning()
                        ->persistent()
                        ->send();
                }),
        ];
    }
}
