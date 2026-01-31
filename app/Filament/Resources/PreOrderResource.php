<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreOrderResource\Pages;
use App\Models\PreOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\PreOrderFulfilledMail;

class PreOrderResource extends Resource
{
    protected static ?string $model = PreOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Order Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_price')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Select::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                        'partially_paid' => 'Partially Paid',
                    ]),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Forms\Components\FileUpload::make('download_file')
                    ->directory('pre-orders')
                    ->downloadable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'danger' => 'unpaid',
                        'success' => 'paid',
                        'warning' => 'partially_paid',
                    ]),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'warning' => 'processing',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('fulfill')
                    ->label('Fulfill & Send')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('Upload Accounts File')
                            ->directory('pre-orders')
                            ->required(),
                    ])
                    ->action(function (PreOrder $record, array $data) {
                        // Update record
                        $record->update([
                            'download_file' => $data['file'],
                            'status' => 'completed',
                            'completed_at' => now(),
                        ]);

                        // Send Email
                        $email = $record->email ?? $record->user->email;
                        if ($email) {
                            try {
                                Mail::to($email)->send(new PreOrderFulfilledMail($record));
                                \Filament\Notifications\Notification::make()
                                    ->title('Order fulfilled and email sent successfully.')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Order fulfilled but email failed to send.')
                                    ->body($e->getMessage())
                                    ->warning()
                                    ->send();
                            }
                        }
                    })
                    ->visible(fn (PreOrder $record) => $record->status !== 'completed' && $record->payment_status === 'paid'),
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
            'index' => Pages\ListPreOrders::route('/'),
            'create' => Pages\CreatePreOrder::route('/create'),
            'edit' => Pages\EditPreOrder::route('/{record}/edit'),
        ];
    }
}
