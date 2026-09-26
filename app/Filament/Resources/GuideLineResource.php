<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuideLineResource\Pages;
use App\Filament\Resources\GuideLineResource\RelationManagers;
use App\Models\Guideline;
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
    protected static ?string $model = Guideline::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Page Setups';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Guideline Details')
                    ->description('Add the title, video reference, and full guideline instructions.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->placeholder('Enter the guideline title')
                            ->required(),
                        TextInput::make('youtube_link')
                            ->label('YouTube Link')
                            ->url()
                            ->placeholder('Paste the YouTube video URL')
                            ->required(),
                        RichEditor::make('details')
                            ->label('Instructions')
                            ->columnSpanFull()
                            ->required(),
                    ])
                    ->columns(2)
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

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_guidelines') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_guidelines') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_guidelines') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_guidelines') ?? false;
    }
}
