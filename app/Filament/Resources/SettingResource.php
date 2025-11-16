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
                TextInput::make('website_name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('Mobile No')
                    ->required(),

                FileUpload::make('favicon')
                    ->image()
                    ->directory('fabicon')
                    ->nullable(),

                FileUpload::make('logo')
                    ->image()
                    ->directory('logo')
                    ->nullable(),

                TextInput::make('email')
                    ->label('Email Address')
                    ->required(),

                TextInput::make('f_link')
                    ->label('Facebook Link'),
                TextInput::make('i_link')
                    ->label('Instagram Link'),

                TextInput::make('t_link')
                    ->label('Telegram Link'),

                TextInput::make('y_link')
                    ->label('Youtube Link'),

                TextInput::make('tw_link')
                    ->label('Twitter Link'),

                TextInput::make('lnkd_link')
                    ->label('Linked In Link'),

                Textarea::make('address')
                    ->columnSpanFull()
                    ->rows(5)
                    ->label('Address')
                    ->required(),
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
                    ->getStateUsing(fn($record) => $record->favicon ? asset('storage/' . $record->favicon) : null)
                    ->square(),
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->getStateUsing(fn($record) => $record->logo ? asset('storage/' . $record->logo) : null)
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

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime(),
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
}
