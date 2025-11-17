<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Filament\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
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

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Page Setups';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('main_title'),
                TextInput::make('sub_title'),
                Textarea::make('title_details')->rows(3),
                FileUpload::make('banner_image')
                    ->image()
                    ->directory('banner')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('main_title'),
                TextColumn::make('sub_title'),
                TextColumn::make('title_details')
                ->formatStateUsing(function ($state) {
                        $words = explode(' ', $state);
                        if (count($words) > 7) {
                            $words = array_slice($words, 0, 7);
                            return implode(' ', $words) . '...';
                        }
                        return $state;
                    }),
                ImageColumn::make('banner_image')
                    ->label('Banner Image')
                    ->getStateUsing(fn($record) => $record->banner_image ? image_path($record->banner_image) : null)
                    ->square(),

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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
