<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderFulfillmentService;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Order Management';

    protected static ?int $navigationSort = 1;

    /**
     * Navigation badge.
     *
     * This is only one COUNT query.
     */
    public static function getNavigationBadge(): ?string
{
    $count = Order::query()
        ->whereIn('order_status', [
            'pending',
            'processing',
        ])
        ->count();

    return $count > 0 ? (string) $count : null;
}


    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([

            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            Forms\Components\Section::make('Customer Details')
                ->description(
                    'Choose the customer for this order or enter a guest email.'
                )
                ->schema([

                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->label('Registered Customer')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->live()
                        ->helperText(
                            'Select a registered user, or leave empty for a guest order.'
                        ),

                    TextInput::make('guest_email')
                        ->email()
                        ->label('Guest Email')
                        ->required(
                            fn (Forms\Get $get): bool =>
                                empty($get('user_id'))
                        )
                        ->visible(
                            fn (Forms\Get $get): bool =>
                                empty($get('user_id'))
                        )
                        ->helperText(
                            'Required if no registered customer is selected.'
                        ),

                    TextInput::make('order_number')
                        ->disabled()
                        ->label('Order Number')
                        ->nullable()
                        ->visible(
                            fn ($livewire): bool =>
                                ! $livewire instanceof Pages\CreateOrder
                        ),

                    TextInput::make('total_price')
                        ->numeric()
                        ->required()
                        ->label('Total Price'),
                ])
                ->columns(2),

            /*
            |--------------------------------------------------------------------------
            | CREATE ORDER ITEMS
            |--------------------------------------------------------------------------
            */

            Forms\Components\Section::make('Order Items')
                ->description(
                    'Add the products included in this order and review line totals.'
                )
                ->visible(
                    fn (string $operation): bool =>
                        $operation === 'create'
                )
                ->schema([

                    Forms\Components\Repeater::make('orderItems')
                        ->relationship()
                        ->schema([

                            Select::make('product_id')
                                ->label('Product')
                                ->relationship('product', 'name')
                                ->required()
                                ->preload()
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(
                                    function (
                                        $state,
                                        Forms\Set $set
                                    ): void {
                                        if (! $state) {
                                            return;
                                        }

                                        $product = Product::query()
                                            ->select([
                                                'id',
                                                'selling_price',
                                                'min_order_qty',
                                            ])
                                            ->find($state);

                                        if (! $product) {
                                            return;
                                        }

                                        $quantity = (int) $product->min_order_qty;
                                        $unitPrice = (float) $product->selling_price;

                                        $set(
                                            'unit_price',
                                            $unitPrice
                                        );

                                        $set(
                                            'quantity',
                                            $quantity
                                        );

                                        $set(
                                            'total_price',
                                            $quantity * $unitPrice
                                        );
                                    }
                                ),

                            TextInput::make('quantity')
                                ->label('Quantity')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->live()
                                ->minValue(
                                    fn (Forms\Get $get): int =>
                                        (int) (
                                            Product::query()
                                                ->whereKey(
                                                    $get('product_id')
                                                )
                                                ->value('min_order_qty')
                                            ?? 1
                                        )
                                )
                                ->maxValue(
                                    fn (Forms\Get $get): int =>
                                        (int) (
                                            Product::query()
                                                ->whereKey(
                                                    $get('product_id')
                                                )
                                                ->value('stock')
                                            ?? 0
                                        )
                                )
                                ->afterStateUpdated(
                                    function (
                                        $state,
                                        Forms\Get $get,
                                        Forms\Set $set
                                    ): void {
                                        $quantity = (float) ($state ?? 0);
                                        $unitPrice = (float) (
                                            $get('unit_price') ?? 0
                                        );

                                        $set(
                                            'total_price',
                                            $quantity * $unitPrice
                                        );
                                    }
                                ),

                            TextInput::make('unit_price')
                                ->label('Unit Price')
                                ->numeric()
                                ->required()
                                ->live()
                                ->afterStateUpdated(
                                    function (
                                        $state,
                                        Forms\Get $get,
                                        Forms\Set $set
                                    ): void {
                                        $unitPrice = (float) ($state ?? 0);
                                        $quantity = (float) (
                                            $get('quantity') ?? 0
                                        );

                                        $set(
                                            'total_price',
                                            $quantity * $unitPrice
                                        );
                                    }
                                ),

                            TextInput::make('total_price')
                                ->label('Line Total')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(),
                        ])
                        ->columns(4)
                        ->live()
                        ->afterStateUpdated(
                            function (
                                Forms\Get $get,
                                Forms\Set $set
                            ): void {
                                $items = $get('orderItems') ?? [];

                                $total = collect($items)
                                    ->sum(
                                        fn (array $item): float =>
                                            (float) ($item['quantity'] ?? 0)
                                            *
                                            (float) ($item['unit_price'] ?? 0)
                                    );

                                $set(
                                    'total_price',
                                    $total
                                );
                            }
                        ),
                ]),

            /*
            |--------------------------------------------------------------------------
            | EDIT ORDER ITEMS
            |--------------------------------------------------------------------------
            */

            Forms\Components\Section::make('Order Items')
                ->description(
                    'View the items included in this manual order.'
                )
                ->visible(
                    fn (string $operation): bool =>
                        $operation === 'edit'
                )
                ->schema([

                    Forms\Components\Placeholder::make(
                        'order_items_list'
                    )
                        ->label('')
                        ->content(
                            function (?Order $record): HtmlString|string {
                                if (! $record) {
                                    return 'No order items found.';
                                }

                                /*
                                 * Eager load product.
                                 * Prevents one query per order item.
                                 */
                                $record->loadMissing(
                                    'orderItems.product'
                                );

                                if ($record->orderItems->isEmpty()) {
                                    return 'No order items found.';
                                }

                                $rows = $record->orderItems
                                    ->map(
                                        function ($item): string {
                                            return '<tr>'
                                                . '<td style="padding:10px;border:1px solid #e5e7eb;">'
                                                . e(
                                                    $item->product?->name
                                                    ?? 'N/A'
                                                )
                                                . '</td>'

                                                . '<td style="padding:10px;border:1px solid #e5e7eb;">'
                                                . e(
                                                    (string) $item->quantity
                                                )
                                                . '</td>'

                                                . '<td style="padding:10px;border:1px solid #e5e7eb;">$'
                                                . e(
                                                    number_format(
                                                        (float) $item->unit_price,
                                                        2
                                                    )
                                                )
                                                . '</td>'

                                                . '<td style="padding:10px;border:1px solid #e5e7eb;">$'
                                                . e(
                                                    number_format(
                                                        (float) $item->total_price,
                                                        2
                                                    )
                                                )
                                                . '</td>'

                                                . '</tr>';
                                        }
                                    )
                                    ->implode('');

                                return new HtmlString(
                                    '<div style="overflow-x:auto;">'
                                    . '<table style="width:100%;border-collapse:collapse;">'
                                    . '<thead>'
                                    . '<tr>'
                                    . '<th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">Product</th>'
                                    . '<th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">Quantity</th>'
                                    . '<th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">Unit Price</th>'
                                    . '<th style="text-align:left;padding:10px;border:1px solid #e5e7eb;">Total Price</th>'
                                    . '</tr>'
                                    . '</thead>'
                                    . '<tbody>'
                                    . $rows
                                    . '</tbody>'
                                    . '</table>'
                                    . '</div>'
                                );
                            }
                        )
                        ->columnSpanFull(),
                ]),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return $table

            /*
             * Automatically update the table every 5 seconds.
             *
             * This is a Livewire background request.
             * It does NOT reload the browser page.
             */
            ->poll('5s')

            ->defaultSort(
                'ordered_at',
                'desc'
            )

            /*
             * IMPORTANT:
             *
             * Eager-load relationships used by table columns/actions.
             *
             * Without this, Laravel can execute additional queries
             * when accessing user relationships.
             */
            ->modifyQueryUsing(
                fn (Builder $query): Builder =>
                    $query
                        ->with([
                            'user:id,name,email',
                        ])
                        ->withCount('deliveries')
            )

            ->columns([

                TextColumn::make('order_number')
                    ->label('Order #')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable()
                    ->placeholder('Guest'),

                TextColumn::make('total_price')
                    ->label('Total Price')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->color(
                        fn (string $state): string =>
                            match ($state) {
                                'paid' => 'success',
                                'unpaid' => 'danger',
                                'partially_paid' => 'warning',
                                default => 'secondary',
                            }
                    ),

                TextColumn::make('order_status')
                    ->label('Order Status')
                    ->badge()
                    ->color(
                        fn (string $state): string =>
                            match ($state) {
                                'pending' => 'warning',
                                'processing' => 'primary',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default => 'secondary',
                            }
                    ),

                TextColumn::make('ordered_at')
                    ->label('Ordered Date')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label('Completed Date')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('deliveries_count')
                    ->label('Delivered Items')
                    ->badge()
                    ->color(
                        fn ($state): string =>
                            (int) $state > 0
                                ? 'success'
                                : 'gray'
                    ),
            ])

            ->filters([
                // Add your filters here.
            ])

            ->actions([

                /*
                |--------------------------------------------------------------------------
                | FULFILL ORDER
                |--------------------------------------------------------------------------
                */

                Tables\Actions\Action::make('fulfillOrder')
                    ->label('Complete & Fulfill')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()

                    ->visible(
                        fn (Order $record): bool =>
                            (
                                auth()->user()?->hasPermissionTo(
                                    'fulfill_orders'
                                ) ?? false
                            )
                            &&
                            $record->order_status !== 'completed'
                            &&
                            $record->payment_status !== 'paid'
                    )

                    ->form([

                        TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->default(
                                fn (Order $record) =>
                                    $record
                                        ->payments()
                                        ->latest()
                                        ->value('transaction_id')
                            )
                            ->disabled(),

                        TextInput::make('amount')
                            ->label('Amount')
                            ->default(
                                fn (Order $record) =>
                                    $record
                                        ->payments()
                                        ->latest()
                                        ->value('amount')
                            )
                            ->disabled(),
                    ])

                    ->action(
                        function (Order $record): void {
                            try {

                                $payment = $record
                                    ->payments()
                                    ->latest()
                                    ->first();

                                $transactionId = $payment?->transaction_id
                                    ?? 'MANUAL-' . uniqid();

                                app(
                                    OrderFulfillmentService::class
                                )->fulfillOrder(
                                    $record,
                                    [
                                        'payment_id' => $transactionId,
                                        'price_amount' => $record->total_price,
                                        'price_currency' => 'USD',
                                    ]
                                );

                                if ($payment) {
                                    $payment->update([
                                        'status' => 'completed',
                                        'paid_at' => now(),
                                    ]);
                                }

                                Notification::make()
                                    ->title(
                                        'Order Fulfilled Successfully'
                                    )
                                    ->success()
                                    ->send();

                            } catch (\Throwable $e) {

                                report($e);

                                Notification::make()
                                    ->title('Fulfillment Failed')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }
                    ),

                /*
                |--------------------------------------------------------------------------
                | RESEND EMAIL
                |--------------------------------------------------------------------------
                */

                Tables\Actions\Action::make('resendEmail')
                    ->label('Resend Email')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->requiresConfirmation()

                    ->visible(
                        fn (Order $record): bool =>
                            $record->order_status === 'completed'
                            &&
                            filled($record->download_file)
                            &&
                            filled(
                                $record->guest_email
                                ?? $record->user?->email
                            )
                    )

                    ->action(
                        function (Order $record): void {
                            try {

                                $recipientEmail =
                                    $record->guest_email
                                    ?? $record->user?->email;

                                if (! $recipientEmail) {
                                    Notification::make()
                                        ->title(
                                            'No recipient email found'
                                        )
                                        ->danger()
                                        ->send();

                                    return;
                                }

                                Mail::to($recipientEmail)
                                    ->send(
                                        new \App\Mail\OrderFulfilledMail(
                                            $record,
                                            $record->download_file
                                        )
                                    );

                                Notification::make()
                                    ->title(
                                        'Email Resent Successfully'
                                    )
                                    ->success()
                                    ->send();

                            } catch (\Throwable $e) {

                                report($e);

                                Notification::make()
                                    ->title(
                                        'Failed to send email'
                                    )
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }
                    ),

                /*
                |--------------------------------------------------------------------------
                | DOWNLOAD ACCOUNTS
                |--------------------------------------------------------------------------
                */

                Tables\Actions\Action::make('downloadAccounts')
                    ->label('Download Accounts')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->url(
                        fn (Order $record): string =>
                            route(
                                'order.download',
                                $record->order_number
                            )
                    )
                    ->openUrlInNewTab()
                    ->visible(
                        fn (Order $record): bool =>
                            $record->order_status === 'completed'
                            &&
                            filled($record->download_file)
                    ),
            ])

            /*
             * IMPORTANT:
             *
             * Do NOT wrap one bulk action in BulkActionGroup.
             *
             * This gives Filament one direct bulk button and avoids
             * unnecessary nested action rendering.
             */
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Delete Selected')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation(),
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public static function getRelations(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | PAGES
    |--------------------------------------------------------------------------
    */

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),

            'create' => Pages\CreateOrder::route('/create'),

            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo(
            'manage_orders'
        ) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo(
            'manage_orders'
        ) ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermissionTo(
            'manage_orders'
        ) ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermissionTo(
            'manage_orders'
        ) ?? false;
    }
}
