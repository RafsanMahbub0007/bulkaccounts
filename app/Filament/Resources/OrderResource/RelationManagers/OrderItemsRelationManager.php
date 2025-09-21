<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Product Selection Dropdown
                Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name') // Assumes a `product` relationship in the OrderItem model
                    ->required()
                    ->preload(),

                // Quantity Input
                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->required(),

                // Unit Price Input
                TextInput::make('unit_price')
                    ->label('Unit Price ($)')
                    ->numeric()
                    ->required(),

                // Total Price (Disabled for auto-calculation)
                TextInput::make('total_price')
                    ->label('Total Price ($)')
                    ->numeric()
                    ->disabled() // Prevents manual edits
                    ->default(0), // Defaults to 0
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_id')
            ->columns([
                // Product Name Column
                TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),

                // Quantity Column
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),

                // Unit Price Column
                TextColumn::make('unit_price')
                    ->label('Unit Price')
                    ->money('USD'),

                // Total Price Column
                TextColumn::make('total_price')
                    ->label('Total Price')
                    ->money('USD'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
