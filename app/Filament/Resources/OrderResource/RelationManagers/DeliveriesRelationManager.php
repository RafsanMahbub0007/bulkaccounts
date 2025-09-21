<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveriesRelationManager extends RelationManager
{
    protected static string $relationship = 'deliveries';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('order_item_id')
                    ->label('Order Item')
                    ->options(function () {
                        // Access the parent order directly
                        $orderId = $this->ownerRecord->id; // Using $this->ownerRecord to access the parent order

                        return \App\Models\OrderItem::where('order_id', $orderId) // Filter by the current order's ID
                            ->with('product') // Eager load the product
                            ->get()
                            ->mapWithKeys(function ($item) {
                                return [$item->id => $item->product->name ?? 'N/A'];
                            });
                    })
                    ->required(),


                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'delivered' => 'Delivered',
                    ])
                    ->default('pending')
                    ->required(),

                DatePicker::make('delivered_at')
                    ->label('Delivered At')
                    ->nullable(),

                Textarea::make('accounts')
                    ->columnSpanFull()
                    ->rows(5)
                    ->label('Accounts Information')
                    ->placeholder('Add account details or file links.')
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('orderItem.product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),

                IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'delivered' => 'heroicon-o-check-circle',
                    })
                    ->colors([
                        'danger' => fn(string $state): bool => $state === 'pending',
                        'success' => fn(string $state): bool => $state === 'delivered',
                    ]),

                TextColumn::make('delivered_at')
                    ->label('Delivered At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('accounts')
                    ->label('Accounts Details')
                    ->limit(50)
                    ->tooltip(fn($record) => $record->accounts ?? 'No details available'), // Corrected Tooltip
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
