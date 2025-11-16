<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ProductAccount;
use Illuminate\Support\Facades\Storage;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    /**
     * Run after the product is created
     */
    protected function afterCreate(): void
    {
        $this->processExcelUpload($this->record);
    }

    /**
     * Process uploaded Excel file for product accounts
     */
    protected function processExcelUpload($product)
    {
        $file = $product->accounts_excel;

        if (!$file) {
            return; // no Excel uploaded
        }

        // Get full path to the file
        $path = Storage::disk('public')->path($file);

        // Read the first sheet
        $rows = Excel::toArray([], $path)[0];

        if (empty($rows) || count($rows) < 2) {
            return; // no data
        }

        // Use first row as header (normalized)
        $headers = array_map('strtolower', array_map('trim', $rows[0]));

        // Remaining rows are data
        $dataRows = array_slice($rows, 1);

        // Update stock as the number of data rows
        $product->stock = count($dataRows);
        $product->save();

        foreach ($dataRows as $row) {
            // Map row values to header keys
            $rowData = array_combine($headers, $row);

            // Skip rows without an email
            if (empty($rowData['email_account'])) {
                continue;
            }

            // Create ProductAccount
            ProductAccount::create([
                'product_id' => $product->id,
                'email' => $rowData['email_account'] ?? '',
                'password_encrypted' => $rowData['email_password'] ? encrypt($rowData['email_password']) : null,
                'two_fa_secret_encrypted' => $rowData['2fa_code'] ? encrypt($rowData['2fa_code']) : null,
                'meta' => [
                    'full_name' => $rowData['full_name'] ?? '',
                    'account_password' => $rowData['account_password'] ?? '',
                    'uid' => $rowData['uid'] ?? '',
                    'recovery_email' => $rowData['recovery_email'] ?? '',
                    'profile_link' => $rowData['profile_link'] ?? '',
                    'create_date' => $rowData['create_date'] ?? '',
                    'download_link' => $rowData['download_link'] ?? '',
                    'username' => $rowData['username'] ?? '',
                    'location' => $rowData['location'] ?? '',
                    'connection' => $rowData['connection'] ?? '',
                    'karma' => $rowData['karma'] ?? '',
                    'followers' => $rowData['followers'] ?? '',
                    'friends' => $rowData['friends'] ?? '',
                    'phone_number' => $rowData['phone_number'] ?? '',
                    'plan_type' => $rowData['plan_type'] ?? '',
                    'card_number' => $rowData['card_number'] ?? '',
                    'expiry_date' => $rowData['expiry_date'] ?? '',
                    'cvv_code' => $rowData['cvv_code'] ?? '',
                    'card_type' => $rowData['card_type'] ?? '',
                    'balance' => $rowData['balance'] ?? '',
                    'storage_capacity' => $rowData['storage_capacity'] ?? '',
                ],
            ]);
        }
    }
}
