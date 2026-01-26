<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        // Reuse form from OrderResource if possible, or define a simplified one.
        // Usually in relation managers we might just want to view or create simple records.
        // For orders, creating a full order from here might be complex due to line items.
        // I'll leave the form simple or just rely on the main OrderResource for creation if preferred.
        // For now, I'll just put a placeholder or basic fields to avoid errors if someone tries to create.
        return OrderResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_number')
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->sortable()
                    ->searchable()
                    ->label('Order #'),

                Tables\Columns\TextColumn::make('total_price')
                    ->money('USD')
                    ->sortable()
                    ->label('Total Price'),

                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'partially_paid' => 'warning',
                        default => 'secondary',
                    })
                    ->label('Payment Status'),

                Tables\Columns\TextColumn::make('order_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    })
                    ->label('Order Status'),

                Tables\Columns\TextColumn::make('ordered_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Ordered Date'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(), // Optional: Allow creating orders from here
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make()
                    ->url(fn ($record) => OrderResource::getUrl('edit', ['record' => $record])), // Link to the full order edit page
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
