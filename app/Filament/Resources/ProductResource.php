<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\SubCategory;
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
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('slug')->unique(Product::class, 'slug', ignoreRecord: true)->maxLength(255),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required()
                    ->reactive(),
                Select::make('subcategory_id')
                    ->label('Sub Category')
                    ->options(fn(callable $get) => $get('category_id') ? SubCategory::where('category_id', $get('category_id'))->pluck('name', 'id') : [])
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
                ->default(0)
                ->disabled()
                ->dehydrated(false),
                TextInput::make('min_order_qty')
                ->numeric()
                ->default(10),
                TagsInput::make('keywords')
                ->placeholder('Add keywords...')
                ->dehydrateStateUsing(fn($state) => is_array($state) ? implode(',', $state) : $state)->nullable(),
                Textarea::make('description')
                ->label('Product Description')
                ->nullable(),
                RichEditor::make('content')
                ->label('Product Content')
                ->columnSpanFull()
                ->nullable(),
                FileUpload::make('accounts_excel')
                    ->label('Upload Accounts Excel/CSV')
                    ->acceptedFileTypes([
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'text/csv',
                        'application/vnd.ms-excel'
                    ])
                    ->directory('account-excels')
                    ->nullable(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('slug'),
                TextColumn::make('category.name')
                ->label('Category')
                ->sortable()
                ->searchable(),
                TextColumn::make('subcategory.name')
                ->label('Sub-Category')
                ->sortable()
                ->searchable(),
                ImageColumn::make('image')
                    ->label('Image')
                    ->getStateUsing(fn($record) => $record->product_image ? asset('storage/' . $record->product_image) : null)
                    ->square(),
                BadgeColumn::make('feature_ids')
                    ->label('Features')
                    ->getStateUsing(fn($record) => ProductFeature::whereIn('id', $record->feature_ids ?? [])->pluck('name')->toArray())
                    ->colors(['success']),
                TextColumn::make('purchase_price')->money('USD'),
                TextColumn::make('selling_price')->money('USD'),
                TextColumn::make('stock'),
                TextColumn::make('min_order_qty')->label('Minimum'),
                TextColumn::make('created_at')->label('Created')->date(),
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
        return [];
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
