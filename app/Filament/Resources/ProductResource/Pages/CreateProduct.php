<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterCreate(): void
    {
        $summary = ProductResource::processExcelUpload($this->record);

        if (isset($summary['status']) && $summary['status'] === 'no_file') {
            Notification::make()
                ->title('Product created — no Excel to import.')
                ->success()
                ->send();
            return;
        }

        if (isset($summary['status']) && $summary['status'] === 'already_processed') {
            Notification::make()
                ->title('Import skipped')
                ->body(implode("\n", $summary['errors']))
                ->warning()
                ->send();
            return;
        }

        if (isset($summary['status']) && $summary['status'] === 'error') {
            Notification::make()
                ->title('Import error')
                ->body(implode("\n", $summary['errors']))
                ->danger()
                ->send();
            return;
        }

        // Success notification summary
        $bodyLines = [];
        $bodyLines[] = "Added: {$summary['added']}";
        $bodyLines[] = "Updated: {$summary['updated']}";
        $bodyLines[] = "Skipped (file dup): {$summary['skipped_file_duplicate']}";
        $bodyLines[] = "Skipped (belongs to other product): {$summary['skipped_other_product']}";
        $bodyLines[] = "Skipped (no/invalid email): {$summary['skipped_no_email']}";
        if (!empty($summary['errors'])) {
            $bodyLines[] = 'Errors: ' . implode('; ', $summary['errors']);
        }

        Notification::make()
            ->title('Import completed')
            ->body(implode("\n", $bodyLines))
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
