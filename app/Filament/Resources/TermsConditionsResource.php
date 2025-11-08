<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TermsConditionsResource\Pages;
use App\Filament\Resources\TermsConditionsResource\RelationManagers;
use App\Models\Terms;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TermsConditionsResource extends Resource
{
    protected static ?string $model = Terms::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Terms & Condition';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               RichEditor::make('terms')
                    ->columnSpanFull()
                    ->label('Write Your Full Terms & Conditions')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('terms')->label('Terms &  Conditions')->limit(50)->html(),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:A')
                    ->label('Terms Created'),
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
            'index' => Pages\ListTermsConditions::route('/'),
            'create' => Pages\CreateTermsConditions::route('/create'),
            'edit' => Pages\EditTermsConditions::route('/{record}/edit'),
        ];
    }
}
