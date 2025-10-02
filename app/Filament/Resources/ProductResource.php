<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->unique(Product::class, 'slug', ignoreRecord: true)
                    ->maxLength(255),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required(),
                Select::make('subcategory_id')
                    ->label('Sub Category')
                    ->relationship('subCategory', 'name')
                    ->required(),
                CheckboxList::make('features')
                    ->label('Features')
                    ->options([
                        '2fa_certified' => '2FA Certified',
                        'mail_verified' => 'Mail Verified',
                        'aged' => 'Aged',
                        'brand_new' => 'Brand New',
                        'eva' => 'EVA',
                        'pva' => 'PVA',
                    ])
                    ->columns(2),
                TextInput::make('price')
                    ->numeric()
                    ->required(),
                TextInput::make('stock')
                    ->numeric()
                    ->default(0),
                TextInput::make('min_order_qty')
                    ->numeric()
                    ->default(10),
                FileUpload::make('product_icon')
                    ->image()
                    ->directory('product-icons')
                    ->nullable(),
                FileUpload::make('product_image')
                    ->image()
                    ->directory('product-images')
                    ->nullable(),
                TagsInput::make('keywords')
                    ->placeholder('Add keywords...')
                    ->dehydrateStateUsing(fn($state) => is_array($state) ? implode(',', $state) : $state)
                    ->nullable(),
                Textarea::make('description')
                    ->label('Product Description')
                    ->nullable(),
                RichEditor::make('content')
                    ->label('Product Content')
                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug'),
                TextColumn::make('category.name')->label('Category')->sortable()->searchable(),
                TextColumn::make('price')
                    ->money('USD'),
                TextColumn::make('stock'),
                TextColumn::make('min_order_qty'),
                TextColumn::make('created_at')
                    ->date(),
                TextColumn::make('updated_at')
                    ->date(),
            ])
            ->filters([ /* Add filters here if needed */])
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
