<?php

namespace App\Imports;

use App\Models\ProductAccount;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class ProductAccountsImport implements ToCollection, WithHeadingRow
{
    protected int $productId;
    protected int $rowCount = 0;

    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $uniqueEmails = [];

        foreach ($rows as $row) {
            // Normalize email
            $email = strtolower(trim($row['email'] ?? ''));

            if (!$email) continue;

            // Skip duplicates in this import
            if (in_array($email, $uniqueEmails)) continue;

            // Check if email already exists for this product
            if (!ProductAccount::where('product_id', $this->productId)->where('email', $email)->exists()) {
                ProductAccount::create([
                    'product_id' => $this->productId,
                    'email' => $email,
                    'password_encrypted' => $row['password'] ?? null,
                    'two_fa_secret_encrypted' => $row['two_fa'] ?? null,
                ]);

                $this->rowCount++;
            }

            $uniqueEmails[] = $email;
        }
    }

    /**
     * Get the number of unique rows inserted
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
