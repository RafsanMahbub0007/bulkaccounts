<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Select::make('type')
                    ->label('Field Type')
                    ->options([
                        'text' => 'Text',
                        'dropdown' => 'Dropdown',
                        'textarea' => 'Textarea',
                        'boolean' => 'Boolean',
                    ])
                    ->required(),

                Select::make('group')
                    ->columnSpanFull()
                    ->label('Group')
                    ->options([
                        'general' => 'General',
                        'display' => 'Display',
                        'seo' => 'SEO',
                        'security' => 'Security',
                        'email' => 'Email',
                        'social_media' => 'Social Media',
                        'integration' => 'Integration',
                        'notifications' => 'Notifications',
                        'payment' => 'Payment',
                        'analytics' => 'Analytics',
                        'other' => 'Other',
                    ])
                    ->searchable() // Allows searching through options
                    ->required()
                    ->placeholder('Select a group'),

                Textarea::make('value')
                    ->columnSpanFull()
                    ->rows(5)
                    ->label('Value')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->sortable(),

                TextColumn::make('group')
                    ->label('Group')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('value')
                    ->label('Value')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
