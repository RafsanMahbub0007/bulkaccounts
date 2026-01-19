<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
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

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 2;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Customer')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->live() // Make it reactive
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



                Forms\Components\Section::make('Order Items')
                    ->schema([
                        Forms\Components\Repeater::make('orderItems')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
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
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(4)
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                $items = $get('orderItems') ?? [];
                                $total = collect($items)->sum(fn ($item) => ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0));
                                $set('total_price', $total);
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->visible(fn (Order $record) => $record->order_status !== 'completed' && $record->payment_status !== 'paid')
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderItemsRelationManager::class,
            RelationManagers\DeliveriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
