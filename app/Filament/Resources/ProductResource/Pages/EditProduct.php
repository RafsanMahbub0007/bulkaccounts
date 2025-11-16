<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\EditRecord;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ProductAccount;
use Illuminate\Support\Facades\Storage;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterSave(): void
    {
        $this->processExcelUpload($this->record);
    }

    protected function processExcelUpload($product)
    {
        if (!$product->accounts_excel) {
            return;
        }

        $filePath = Storage::disk('public')->path($product->accounts_excel);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray(null, true, true, true);
        if (empty($rows)) return;

        // Headers (first row)
        $headers = array_map('strtolower', array_map('trim', array_shift($rows)));

        $validRowsCount = 0;

        foreach ($rows as $row) {
            // Stop at first completely empty row
            if (count(array_filter($row, fn($cell) => trim((string)$cell) !== '')) === 0) {
                break;
            }

            $data = array_combine($headers, array_map('trim', array_values($row)));

            $email = $data['email_account'] ?? '';
            if (empty($email)) continue;

            // Update existing account or create new one
            ProductAccount::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'email' => $email,
                ],
                [
                    'password_encrypted' => $data['email_password'] ?? null,
                    'two_fa_secret_encrypted' => $data['2fa_code'] ?? null,
                    'meta' => [
                        'full_name' => $data['full_name'] ?? '',
                        'account_password' => $data['account_password'] ?? '',
                        'uid' => $data['uid'] ?? '',
                        'recovery_email' => $data['recovery_email'] ?? '',
                        'profile_link' => $data['profile_link'] ?? '',
                        'create_date' => $data['create_date'] ?? '',
                        'download_link' => $data['download_link'] ?? '',
                        'username' => $data['username'] ?? '',
                        'location' => $data['location'] ?? '',
                        'connection' => $data['connection'] ?? '',
                        'karma' => $data['karma'] ?? '',
                        'followers' => $data['followers'] ?? '',
                        'friends' => $data['friends'] ?? '',
                        'phone_number' => $data['phone_number'] ?? '',
                        'plan_type' => $data['plan_type'] ?? '',
                        'card_number' => $data['card_number'] ?? '',
                        'expiry_date' => $data['expiry_date'] ?? '',
                        'cvv_code' => $data['cvv_code'] ?? '',
                        'card_type' => $data['card_type'] ?? '',
                        'balance' => $data['balance'] ?? '',
                        'storage_capacity' => $data['storage_capacity'] ?? '',
                    ],
                ]
            );

            $validRowsCount++;
        }
        $product->stock = $validRowsCount;
        $product->save();
    }
}
