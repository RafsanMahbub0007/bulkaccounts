<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\EditRecord;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductAccountsImport;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
 protected function afterCreate(): void
    {
        $this->processExcelUpload($this->record);
    }

    protected function processExcelUpload($product)
    {
        $file = $product->accounts_excel;

        if (!$file) {
            return;
        }

        $path = Storage::disk('public')->path($file);

        $rows = Excel::toArray([], $path)[0];

        foreach ($rows as $index => $row) {

            if ($index === 0) continue; // skip header row

            ProductAccount::create([
                'product_id'   => $product->id,
                'email'        => $row[1] ?? '',
                'password_encrypted' => encrypt($row[2] ?? ''),
                'two_fa_secret_encrypted' => encrypt($row[10] ?? ''),
                'meta' => [
                    'full_name' => $row[0] ?? '',
                    'account_password' => $row[3] ?? '',
                    'uid' => $row[4] ?? '',
                    'recovery_email' => $row[5] ?? '',
                    'profile_link' => $row[6] ?? '',
                    'create_date' => $row[7] ?? '',
                    'download_link' => $row[8] ?? '',
                    'username' => $row[11] ?? '',
                    'location' => $row[12] ?? '',
                    'connection' => $row[13] ?? '',
                    'karma' => $row[14] ?? '',
                    'followers' => $row[15] ?? '',
                    'friends' => $row[16] ?? '',
                    'phone_number' => $row[17] ?? '',
                    'plan_type' => $row[18] ?? '',
                    'card_number' => $row[19] ?? '',
                    'expiry_date' => $row[20] ?? '',
                    'cvv_code' => $row[21] ?? '',
                    'card_type' => $row[22] ?? '',
                    'balance' => $row[23] ?? '',
                    'storage_capacity' => $row[24] ?? '',
                ]
            ]);
        }
    }
}

