<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeoSettingResource\Pages;
use App\Filament\Resources\SeoSettingResource\RelationManagers;
use App\Models\SeoSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeoSettingResource extends Resource
{
    protected static ?string $model = SeoSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'System Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('page_name')
                    ->label('Page Name (e.g., home, about, contact)')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->maxLength(255),
                Forms\Components\Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(3),
                Forms\Components\Textarea::make('meta_keywords')
                    ->label('Meta Keywords')
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('meta_title')->limit(50),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
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
            'index' => Pages\ListSeoSettings::route('/'),
            'create' => Pages\CreateSeoSetting::route('/create'),
            'edit' => Pages\EditSeoSetting::route('/{record}/edit'),
        ];
    }
}
