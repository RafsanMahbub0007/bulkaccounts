<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Filament\Resources\ProductResource;

class SyncProductSheets extends Command
{
    protected $signature = 'products:sync-sheets';
    protected $description = 'Sync Google Sheets for all products';

    public function handle()
    {
        Product::whereNotNull('google_sheet_url')
            ->each(fn ($product) =>
                ProductResource::syncSheet($product)
            );

        $this->info('Product sheets synced successfully.');
    }
}
