<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Services\OrderFulfillmentService;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Order Management';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('order_status', ['pending', 'processing'])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Details')
                    ->description('Choose the customer for this order or enter a guest email.')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Registered Customer')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->live()
                            ->helperText('Select a registered user, or leave empty for a guest order.'),
                        TextInput::make('guest_email')
                            ->email()
                            ->label('Guest Email')
                            ->required(fn (Forms\Get $get) => empty($get('user_id')))
                            ->visible(fn (Forms\Get $get) => empty($get('user_id')))
                            ->helperText('Required if no registered customer is selected.'),
                        TextInput::make('order_number')
                            ->disabled()
                            ->label('Order Number')
                            ->nullable()
                            ->visible(fn ($livewire) => ! $livewire instanceof Pages\CreateOrder),
                        TextInput::make('total_price')
                            ->numeric()
                            ->required()
                            ->label('Total Price'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Order Items')
                    ->description('Add the products included in this order and review line totals.')
                    ->visible(fn (string $operation): bool => $operation === 'create')
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
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        $product = \App\Models\Product::find($state);
                                        if ($product) {
                                            $set('unit_price', $product->selling_price);
                                            $set('quantity', $product->min_order_qty); // Set min qty
                                            $set('total_price', $product->min_order_qty * $product->selling_price);
                                        }
                                    }),
                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->reactive()
                                    ->minValue(fn (Forms\Get $get) =>
                                        \App\Models\Product::find($get('product_id'))?->min_order_qty ?? 1
                                    )
                                    ->maxValue(fn (Forms\Get $get) =>
                                        \App\Models\Product::find($get('product_id'))?->stock ?? 0
                                    )
                                    ->afterStateUpdated(fn ($state, Forms\Get $get, Forms\Set $set) =>
                                        $set('total_price', $state * $get('unit_price'))
                                    ),
                                TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->minValue(fn (Forms\Get $get) =>
                                        \App\Models\Product::find($get('product_id'))?->selling_price ?? 0
                                    )
                                    ->afterStateUpdated(fn ($state, Forms\Get $get, Forms\Set $set) =>
                                        $set('total_price', $state * $get('quantity'))
                                    ),
                                TextInput::make('total_price')
                                    ->label('Line Total')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(4)
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                $items = $get('orderItems') ?? [];
                                $total = collect($items)->sum(fn ($item) => ((float)($item['quantity'] ?? 0)) * ((float)($item['unit_price'] ?? 0)));
                                $set('total_price', $total);
                            }),
                    ]),
                Forms\Components\Section::make('Order Items')
                    ->description('View the items included in this manual order.')
                    ->visible(fn (string $operation): bool => $operation === 'edit')
                    ->schema([
                        Forms\Components\Placeholder::make('order_items_list')
                            ->label('')
                            ->content(function (?Order $record): HtmlString|string {
                                if (! $record) {
                                    return 'No order items found.';
                                }

                                $record->loadMissing('orderItems.product');

                                if ($record->orderItems->isEmpty()) {
                                    return 'No order items found.';
                                }

                                $rows = $record->orderItems->map(function ($item): string {
                                    return '<tr>'
                                        . '<td style="padding:10px;border:1px solid #e5e7eb;">' . e($item->product?->name ?? 'N/A') . '</td>'
                                        . '<td style="padding:10px;border:1px solid #e5e7eb;">' . e((string) $item->quantity) . '</td>'
                                        . '<td style="padding:10px;border:1px solid #e5e7eb;">$' . e(number_format((float) $item->unit_price, 2)) . '</td>'
                                        . '<td style="padding:10px;border:1px solid #e5e7eb;">$' . e(number_format((float) $item->total_price, 2)) . '</td>'
                                        . '</tr>';
                                })->implode('');

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
                                    . '<tbody>' . $rows . '</tbody>'
                                    . '</table>'
                                    . '</div>'
                                );
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('5s')
             ->defaultSort('ordered_at', 'desc')
            ->columns([
                TextColumn::make('order_number')
                    ->sortable()
                    ->searchable()
                    ->label('Order #'),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_price')
                    ->money('USD')
                    ->sortable()
                    ->label('Total Price'),

                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'partially_paid' => 'warning',
                        default => 'secondary',
                    })
                    ->label('Payment Status'),

                TextColumn::make('order_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    })
                    ->label('Order Status'),

                TextColumn::make('ordered_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Ordered Date'),

                TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Completed Date'),

                TextColumn::make('deliveries_count')
                    ->counts('deliveries')
                    ->badge()
                    ->color(fn (string $state): string => (int) $state > 0 ? 'success' : 'gray')
                    ->label('Delivered Items'),
            ])
            ->filters([
                // Filters can be added here
            ])
            ->actions([
                Tables\Actions\Action::make('fulfillOrder')
                    ->label('Complete & Fulfill')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record) => (auth()->user()?->hasPermissionTo('fulfill_orders') ?? false) && $record->order_status !== 'completed' && $record->payment_status !== 'paid')
                    ->form([
                        TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->default(fn (Order $record) => $record->payments()->latest()->first()?->transaction_id)
                            ->disabled(),
                        TextInput::make('amount')
                            ->label('Amount')
                            ->default(fn (Order $record) => $record->payments()->latest()->first()?->amount)
                            ->disabled(),
                    ])
                    ->action(function (Order $record) {
                        try {
                            // Get the existing payment or create a new one
                            $payment = $record->payments()->latest()->first();
                            $transactionId = $payment ? $payment->transaction_id : 'MANUAL-' . uniqid();

                            app(OrderFulfillmentService::class)->fulfillOrder($record, [
                                'payment_id' => $transactionId,
                                'price_amount' => $record->total_price,
                                'price_currency' => 'USD'
                            ]);

                            // Update the pending payment status if it exists
                            if ($payment) {
                                $payment->update(['status' => 'completed', 'paid_at' => now()]);
                            }

                            Notification::make()
                                ->title('Order Fulfilled Successfully')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Fulfillment Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('resendEmail')
                    ->label('Resend Email')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record) => $record->order_status === 'completed' && $record->download_file && !empty($record->guest_email))
                    ->action(function (Order $record) {
                        try {
                            $recipientEmail = $record->guest_email ?? $record->user?->email;

                            if (!$recipientEmail) {
                                Notification::make()
                                    ->title('No recipient email found')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            \Illuminate\Support\Facades\Mail::to($recipientEmail)
                                ->send(new \App\Mail\OrderFulfilledMail($record, $record->download_file));

                            Notification::make()
                                ->title('Email Resent Successfully')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Failed to send email')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('downloadAccounts')
                    ->label('Download Accounts')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->url(fn (Order $record) => route('order.download', $record->order_number))
                    ->openUrlInNewTab()
                    ->visible(fn (Order $record) => $record->order_status === 'completed' && filled($record->download_file)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_orders') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_orders') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_orders') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_orders') ?? false;
    }
}
