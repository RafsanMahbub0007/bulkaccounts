<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuideLineResource\Pages;
use App\Filament\Resources\GuideLineResource\RelationManagers;
use App\Models\GuideLine;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GuideLineResource extends Resource
{
    protected static ?string $model = GuideLine::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Page Setups';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required(),
                TextInput::make('youtube_link')
                    ->required(),
                RichEditor::make('details')
                    ->label('Instructions')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('youtube_link')
                    ->label('Youtube Link'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuideLines::route('/'),
            'create' => Pages\CreateGuideLine::route('/create'),
            'edit' => Pages\EditGuideLine::route('/{record}/edit'),
        ];
    }
}
