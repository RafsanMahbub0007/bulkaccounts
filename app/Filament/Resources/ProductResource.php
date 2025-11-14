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
                    ->splitKeys([','])
                    ->dehydrateStateUsing(fn($state) => is_array($state) ? implode(',', $state) : $state)->nullable(),
                Textarea::make('description')
                    ->label('Product Description')
                    ->nullable(),
                RichEditor::make('content')
                    ->label('Product Content')
                    ->columnSpanFull()
                    ->nullable(),
                FileUpload::make('accounts_excel')
                    ->label('Upload Excel (.xls)')
                    ->acceptedFileTypes([
    'application/vnd.ms-excel', // .xls
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
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
    protected static function mutateFormDataBeforeCreate(array $data): array
    {

        if (empty($data['product_image']) && !empty($data['subcategory_id'])) {
            $subcategory = SubCategory::find($data['subcategory_id']);
            if ($subcategory && $subcategory->image) {
                $data['product_image'] = $subcategory->image;
            }
        }
        return $data;
    }

    protected static function mutateFormDataBeforeSave(array $data): array
    {

        if (empty($data['product_image']) && !empty($data['subcategory_id'])) {
            $subcategory = SubCategory::find($data['subcategory_id']);
            if ($subcategory && $subcategory->image) {
                $data['product_image'] = $subcategory->image;
            }
        }
        return $data;
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
    public static function processExcelUpload(Product $product)
{
    if (!$product->accounts_excel) {
        return;
    }

    $filePath = storage_path('app/public/' . $product->accounts_excel);

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, true);

    // Remove headers
    $headers = array_shift($rows);

    foreach ($rows as $row) {

        $data = array_combine($headers, array_values($row));

        if (!isset($data['email_account'])) {
            continue; // Skip blank rows
        }

        \App\Models\ProductAccount::create([
            'product_id' => $product->id,
            'email' => $data['email_account'],

            // Encrypted fields
            'password_encrypted' => encrypt($data['email_password'] ?? ''),
            'two_fa_secret_encrypted' => encrypt($data['2fa_code'] ?? ''),

            // Everything else goes in JSON
            'meta' => [
                'full_name' => $data['full_name'] ?? '',
                'account_password' => $data['account_password'] ?? '',
                'uid' => $data['uid'] ?? '',
                'recovery_email' => $data['recovery_email'] ?? '',
                'profile_link' => $data['profile_link'] ?? '',
                'create_date' => $data['create_date'] ?? '',
                'download_link' => $data['download_link'] ?? '',
                'username' => $data['username'] ?? '',
                'location' => $data['location'] ?? '',
                'connection' => $data['connection'] ?? '',
                'karma' => $data['karma'] ?? '',
                'followers' => $data['followers'] ?? '',
                'friends' => $data['friends'] ?? '',
                'phone_number' => $data['phone_number'] ?? '',
                'plan_type' => $data['plan_type'] ?? '',
                'card_number' => $data['card_number'] ?? '',
                'expiry_date' => $data['expiry_date'] ?? '',
                'cvv_code' => $data['cvv_code'] ?? '',
                'card_type' => $data['card_type'] ?? '',
                'balance' => $data['balance'] ?? '',
                'storage_capacity' => $data['storage_capacity'] ?? '',
            ],
        ]);
    }
}

}
