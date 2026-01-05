<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductAccountResource\Pages;
use App\Models\Product;
use App\Models\ProductAccount;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class ProductAccountResource extends Resource
{
    protected static ?string $model = ProductAccount::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    /**
     * Update product stock based on unsold accounts
     */
    protected static function updateProductStock(int $productId): void
    {
        Product::whereId($productId)->update([
            'stock' => ProductAccount::where('product_id', $productId)
                ->where('status', 'unsold')
                ->count(),
        ]);
    }

    /**
     * Google Sheet auto sync
     */
    protected static function autoSync(ProductAccount $account): void
    {
        $product = $account->product;

        if ($product && $product->google_sheet_url) {
            ProductResource::syncSheet($product);
        }
    }

    /**
     * Validate bulk selection (must be same status)
     */
    protected static function selectedStatusOrFail($records): ?string
    {
        $statuses = $records->pluck('status')->unique();
        return $statuses->count() === 1 ? $statuses->first() : null;
    }

    protected static function mixedStatusWarning(): void
    {
        Notification::make()
            ->title('Invalid Selection')
            ->body('Please select Sold, UnSold, or Banned products together only for bulk action.')
            ->warning()
            ->send();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(
                        query: fn ($query, $search) =>
                            $query->whereHas(
                                'product',
                                fn ($q) => $q->where('name', 'like', "%{$search}%")
                            )
                    ),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'unsold',
                        'warning' => 'sold',
                        'danger'  => 'banned',
                    ])
                    ->icons([
                        'heroicon-o-check-circle'     => 'unsold',
                        'heroicon-o-currency-dollar' => 'sold',
                        'heroicon-o-x-circle'        => 'banned',
                    ]),

                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'unsold' => 'UnSold',
                        'sold'   => 'Sold',
                        'banned' => 'Banned',
                    ])
            ])

            // =========================
            // BULK ACTIONS
            // =========================
            ->bulkActions([

                // MARK AS SOLD
                Tables\Actions\BulkAction::make('mark_sold')
                    ->label('Mark as Sold')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        $status = self::selectedStatusOrFail($records);

                        if (!in_array($status, ['unsold', 'banned'])) {
                            self::mixedStatusWarning();
                            return;
                        }

                        DB::transaction(function () use ($records) {
                            $products = [];

                            foreach ($records as $record) {
                                if (!$record->order_item_id) {
                                    $record->update(['status' => 'sold']);
                                    $products[] = $record->product_id;
                                    self::autoSync($record);
                                }
                            }

                            foreach (array_unique($products) as $productId) {
                                self::updateProductStock($productId);
                            }
                        });

                        Notification::make()
                            ->title('Accounts marked as sold')
                            ->success()
                            ->send();
                    }),

                // MARK AS UNSOLD
                Tables\Actions\BulkAction::make('mark_unsold')
                    ->label('Mark as UnSold')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        $status = self::selectedStatusOrFail($records);

                        if (!in_array($status, ['sold', 'banned'])) {
                            self::mixedStatusWarning();
                            return;
                        }

                        DB::transaction(function () use ($records) {
                            $products = [];

                            foreach ($records as $record) {
                                if (!$record->order_item_id) {
                                    $record->update(['status' => 'unsold']);
                                    $products[] = $record->product_id;
                                    self::autoSync($record);
                                }
                            }

                            foreach (array_unique($products) as $productId) {
                                self::updateProductStock($productId);
                            }
                        });

                        Notification::make()
                            ->title('Accounts marked as unsold')
                            ->success()
                            ->send();
                    }),

                // MARK AS BANNED
                Tables\Actions\BulkAction::make('mark_banned')
                    ->label('Mark as Banned')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        $status = self::selectedStatusOrFail($records);

                        if (!in_array($status, ['sold', 'unsold'])) {
                            self::mixedStatusWarning();
                            return;
                        }

                        DB::transaction(function () use ($records) {
                            $products = [];

                            foreach ($records as $record) {
                                $record->update(['status' => 'banned']);
                                $products[] = $record->product_id;
                                self::autoSync($record);
                            }

                            foreach (array_unique($products) as $productId) {
                                self::updateProductStock($productId);
                            }
                        });

                        Notification::make()
                            ->title('Accounts banned successfully')
                            ->danger()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductAccounts::route('/'),
        ];
    }
}
