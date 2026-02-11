<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OrderAccountsExport implements WithMultipleSheets
{
    protected $accounts;

    public function __construct(Collection $accounts)
    {
        $this->accounts = $accounts;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Group accounts by product ID
        $grouped = $this->accounts->groupBy('product_id');

        foreach ($grouped as $productId => $accounts) {
            // Get product name from the first account in the group
            $firstAccount = $accounts->first();

            // Fallback name if relation is missing (though it should exist)
            $title = $firstAccount->product ? $firstAccount->product->name : "Product {$productId}";

            // Clean title for Excel (remove invalid chars if necessary)
            $title = str_replace(['*', ':', '/', '\\', '?', '[', ']'], '', $title);

            $sheets[] = new OrderProductSheet($accounts, $title);
        }

        return $sheets;
    }
}
