<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\subCategory;
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
use Filament\Tables\Columns\BadgeColumn;

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
                    ->required()
                    ->reactive(),
                Select::make('subcategory_id')
                    ->label('Sub Category')
                    ->options(function (callable $get) {
                        $categoryId = $get('category_id');
                        if (!$categoryId) {
                            return [];
                        }
                        return subCategory::where('category_id', $categoryId)
                            ->pluck('name', 'id');
                    })
                    ->required()
                    ->reactive()
                    ->disabled(fn(callable $get) => !$get('category_id')),
                CheckboxList::make('feature_ids')
                    ->label('Features')
                    ->options(ProductFeature::pluck('name', 'id'))
                    ->columns(2),
                FileUpload::make('product_image')
                    ->image()
                    ->directory('product-images')
                    ->nullable(),
                TextInput::make('purchase_price')
                    ->numeric()
                    ->required(),
                TextInput::make('selling_price')
                    ->numeric()
                    ->required(),
                TextInput::make('stock')
                    ->numeric()
                    ->default(0),
                TextInput::make('min_order_qty')
                    ->numeric()
                    ->default(10),

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
                TextColumn::make('subcategory.name')->label('Sub-Category')->sortable()->searchable(),
                BadgeColumn::make('feature_ids')
                    ->label('Features')
                    ->getStateUsing(function ($record) {
                        $features = $record->feature_ids ?? [];
                        return \App\Models\ProductFeature::whereIn('id', $features)->pluck('name')->toArray();
                    })
                    ->colors(['success']),
                TextColumn::make('price')
                    ->money('USD'),
                TextColumn::make('stock'),
                TextColumn::make('min_order_qty')
                    ->label('Minimum'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->date(),

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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
