<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Details')
                    ->description('Select the related order, payment method, and transaction details.')
                    ->schema([
                        Select::make('order_id')
                            ->relationship('order', 'order_number')
                            ->label('Related Order')
                            ->required(),
                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'credit_card' => 'Credit Card',
                                'debit_card' => 'Debit Card',
                                'paypal' => 'PayPal',
                                'apple_pay' => 'Apple Pay',
                                'google_pay' => 'Google Pay',
                                'bank_transfer' => 'Bank Transfer',
                                'cryptocurrency' => 'Cryptocurrency',
                                'cash_on_delivery' => 'Cash on Delivery',
                                'other' => 'Other',
                            ])
                            ->required(),
                        TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->unique(ignoreRecord: true)
                            ->nullable(),
                        DatePicker::make('paid_at')
                            ->label('Paid Date')
                            ->nullable(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Amount and Status')
                    ->description('Set the paid amount, currency, and current payment status.')
                    ->schema([
                        TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->required(),
                        TextInput::make('currency')
                            ->label('Currency')
                            ->default('USD')
                            ->maxLength(3)
                            ->required(),
                        Select::make('status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->default('pending')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Order Column
                TextColumn::make('order.order_number')
                    ->label('Order')
                    ->sortable()
                    ->searchable(),

                // Payment Method Column
                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->sortable(),

                // Amount Column
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('USD')
                    ->sortable(),

                // Currency Column
                TextColumn::make('currency')
                    ->label('Currency')
                    ->sortable(),

                // Payment Status Column (IconColumn)
                IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'completed' => 'heroicon-o-check-circle',
                        'failed' => 'heroicon-o-x-circle',
                        'refunded' => 'heroicon-o-arrow-uturn-left',
                    })
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'warning' => 'refunded',
                    ]),

                // Paid At Column
                TextColumn::make('paid_at')
                    ->label('Paid At')
                    ->dateTime(),

                // Created At Column
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            //
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_payments') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_payments') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_payments') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_payments') ?? false;
    }
}
