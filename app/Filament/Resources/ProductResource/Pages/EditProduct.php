<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\EditRecord;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductAccountsImport;
use Filament\Notifications\Notification;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

protected function afterSave(): void
{
    $product = $this->record;

    $uploaded = $this->form->getState()['accounts_excel'] ?? null;

    // If no new file uploaded or just the existing file is present, do nothing
    if (!$uploaded || is_string($uploaded)) {
        return;
    }

    // $uploaded is a new file upload (array)
    $filePath = storage_path('app/public/' . $uploaded[0]['path']);

    // Import accounts
    $import = new ProductAccountsImport($product->id);
    Excel::import($import, $filePath);

    // Update stock
    $product->stock = $import->getRowCount();
    $product->save();

    Notification::make()
        ->title('Accounts Imported')
        ->success()
        ->body("Imported {$product->stock} unique accounts. Duplicates removed automatically.")
        ->send();
}


}
