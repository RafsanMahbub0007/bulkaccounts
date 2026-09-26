<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Page Setups';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Website Details')
                    ->description('Set the main business identity and contact details for the website.')
                    ->schema([
                        TextInput::make('website_name')
                            ->label('Website Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Mobile Number')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required(),
                        Textarea::make('address')
                            ->columnSpanFull()
                            ->rows(5)
                            ->label('Business Address')
                            ->required(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Branding')
                    ->description('Upload the main logo and favicon used across the site.')
                    ->schema([
                        FileUpload::make('favicon')
                            ->label('Favicon')
                            ->image()
                            ->directory('fabicon')
                            ->nullable(),
                        FileUpload::make('logo')
                            ->label('Website Logo')
                            ->image()
                            ->directory('logo')
                            ->nullable(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Social and Support Links')
                    ->description('Add all public social links and customer support links in one place.')
                    ->schema([
                        TextInput::make('f_link')->label('Facebook Link'),
                        TextInput::make('i_link')->label('Instagram Link'),
                        TextInput::make('t_link')->label('Telegram Link'),
                        TextInput::make('y_link')->label('YouTube Link'),
                        TextInput::make('tw_link')->label('Twitter Link'),
                        TextInput::make('lnkd_link')->label('LinkedIn Link'),
                        TextInput::make('pre_order_link')->label('Pre Order Link'),
                        TextInput::make('sup_wa_link')->label('Support WhatsApp Link'),
                        TextInput::make('sup_tele_link')->label('Support Telegram Link'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Manual Payment Information')
                    ->description('Store the wallet address and QR code for manual payment collection.')
                    ->schema([
                        FileUpload::make('manual_pay_qr')
                            ->label('Manual Payment QR Image')
                            ->image()
                            ->directory('pay_qr')
                            ->nullable(),
                        TextInput::make('Manual_pay_wallet')
                            ->label('Manual Payment Wallet Address'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('website_name')
                    ->label('website name'),

                TextColumn::make('phone')
                    ->label('Mobile No'),

                ImageColumn::make('favicon')
                    ->label('Favicon')
                    ->getStateUsing(fn($record) => $record->favicon ? image_path($record->favicon) : null)
                    ->square(),
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->getStateUsing(fn($record) => $record->logo ? image_path($record->logo) : null)
                    ->square(),
                TextColumn::make('email')
                    ->label('Email Address'),

                TextColumn::make('f_link')
                    ->label('Facebook Link'),

                TextColumn::make('i_link')
                    ->label('Instagram Link'),

                TextColumn::make('t_link')
                    ->label('Telegram Link'),

                TextColumn::make('tw_link')
                    ->label('Twitter Link'),

                TextColumn::make('lnkd_link')
                    ->label('Linked In Link'),

                TextColumn::make('y_link')
                    ->label('Youtube Link'),

                TextColumn::make('address')
                    ->label('Address'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->label('Actions'),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_settings') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_settings') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_settings') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_settings') ?? false;
    }
}
