<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\ProductAccount;
use App\Models\ProductFeature;
use App\Models\SubCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Google\Client;
use Google\Service\Sheets;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('stock', 0)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    /* ================= FORM ================= */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Product Details')
                ->description('Enter the main product information used in the catalog.')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Product Name')
                        ->required(),
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\Select::make('category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->required()
                        ->reactive(),
                    Forms\Components\Select::make('subcategory_id')
                        ->label('Sub Category')
                        ->options(
                            fn($get) =>
                            $get('category_id')
                                ? SubCategory::where('category_id', $get('category_id'))->pluck('name', 'id')
                                : []
                        )
                        ->required()
                        ->disabled(fn($get) => !$get('category_id')),
                    Forms\Components\TextInput::make('display_order')
                        ->label('Display Order')
                        ->numeric()
                        ->default(0),
                    Toggle::make('is_active')
                        ->label('Active Status')
                        ->default(false),
                ])
                ->columns(2),
            Forms\Components\Section::make('Pricing and Inventory')
                ->description('Manage the product cost, selling price, and minimum order quantity.')
                ->schema([
                    Forms\Components\TextInput::make('purchase_price')
                        ->label('Purchase Price')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('selling_price')
                        ->label('Selling Price')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('min_order_qty')
                        ->label('Minimum Order Quantity')
                        ->numeric()
                        ->default(1),
                    Forms\Components\TextInput::make('google_sheet_id')
                        ->label('Google Sheet ID')
                        ->helperText('This sheet is used to sync product accounts.')
                        ->required(),
                ])
                ->columns(2),
            Forms\Components\Section::make('Features and SEO')
                ->description('Attach product features and add SEO metadata.')
                ->schema([
                    Forms\Components\CheckboxList::make('feature_ids')
                        ->label('Product Features')
                        ->options(ProductFeature::pluck('name', 'id'))
                        ->columns(2)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('meta_title')
                        ->label('Meta Title')
                        ->maxLength(255),
                    TagsInput::make('keywords')
                        ->label('Meta Keywords')
                        ->splitKeys([','])
                            ->afterStateHydrated(function (TagsInput $component, $state) {
                                $component->state($state ? explode(',', $state) : []);
                            })
                            ->dehydrateStateUsing(fn($state) => is_array($state) ? implode(',', $state) : $state)
                            ->nullable()
                            ->columnSpanFull(),
                    Forms\Components\Textarea::make('description')
                        ->label('Meta Description')
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('content')
                        ->label('Detailed Content / Description for user panel')
                        ->columnSpanFull(),
                ])
        ]);
    }

    /* ================= TABLE ================= */
    public static function table(Table $table): Table
    {
        return $table
            ->poll('1s')
            ->defaultSort('display_order', 'asc')
            ->actionsColumnLabel('Operations')
            ->actionsAlignment('center')
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('display_order')
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('stock')
                    ->badge()
                    ->color(fn ($state) => $state === 0 ? 'danger' : 'success')
                    ->formatStateUsing(fn ($state) => $state === 0 ? 'Out of Stock' : $state),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
            ])
            ->actions([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('openSheet')
                        ->label('Open Sheet')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn ($record) =>
                            "https://docs.google.com/spreadsheets/d/{$record->google_sheet_id}"
                        )
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('syncSheet')
                        ->label('Sync Sheet')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->visible(fn () => auth()->user()?->hasPermissionTo('sync_product_sheet') ?? false)
                        ->action(fn ($record) => static::syncSheet($record)),
                    Tables\Actions\DeleteAction::make(),

            ]);
    }


    /* ================= GOOGLE CLIENT ================= */
    private static function sheets(): Sheets
    {
        $client = new Client();
        $client->setApplicationName('Product Sheet Sync');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(config('services.google.credentials'));

        return new Sheets($client);
    }

    private static function mapStatus(string $tab): string
    {
        return match (strtolower(trim($tab))) {
            'sold'   => 'sold',
            'banned' => 'banned',
            default  => 'unsold',
        };
    }

    /* ================= SYNC ENGINE ================= */
    public static function syncSheet(Product $product, bool $notify = true): void
    {
        try {
            $service = static::sheets();

            DB::transaction(function () use ($service, $product) {

                $spreadsheet = $service->spreadsheets->get($product->google_sheet_id);
                $tabs = $spreadsheet->getSheets();

                $seen = [];

                foreach ($tabs as $sheet) {
                    $tab = $sheet->getProperties()->getTitle();
                    $status = static::mapStatus($tab);

                    $response = $service->spreadsheets_values
                        ->get($product->google_sheet_id, "{$tab}!A1:Z");

                    $rows = $response->getValues();
                    if (!$rows || count($rows) < 2) continue;

                    // Lowercase & trim headers
                    $headers = array_map(fn($h) => strtolower(trim($h)), array_shift($rows));
                    $emailIndex = array_search('email', $headers);
                    if ($emailIndex === false) continue;

                    foreach ($rows as $index => $row) {
                        if (!isset($row[$emailIndex])) continue;

                        $email = strtolower(trim($row[$emailIndex]));
                        if (isset($seen[$email])) continue;
                        $seen[$email] = true;

                        $row = array_pad($row, count($headers), '');

                        // Build meta excluding 'email'
                        $data = array_values(
                            array_filter(
                                $row,
                                fn($value, $index) => strtolower($headers[$index]) !== 'email',
                                ARRAY_FILTER_USE_BOTH
                            )
                        );

                        // Filter meta_headers: all headers except 'email' and reindex array
                        $metaHeaders = array_values(array_filter($headers, fn($h) => $h !== 'email'));

                        ProductAccount::updateOrCreate(
                            ['product_id' => $product->id, 'email' => $email],
                            [
                                'status'       => $status,
                                'meta'         => $data,
                                'meta_headers' => $metaHeaders,
                                'row_index'    => $index,
                            ]
                        );
                    }
                }

                // Prune accounts that are no longer in the sheet
                if (!empty($seen)) {
                    ProductAccount::where('product_id', $product->id)
                        ->whereNotIn('email', array_keys($seen))
                        ->delete();
                }

                $product->update([
                    'stock' => ProductAccount::where('product_id', $product->id)
                        ->where('status', 'unsold')
                        ->count(),
                ]);
            });

            if ($notify) {
                Notification::make()
                    ->title('Sheet synced successfully')
                    ->success()
                    ->send();
            }
        } catch (\Throwable $e) {
            if ($notify) {
                Notification::make()
                    ->title('Sheet sync failed')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            } else {
                throw $e; // Re-throw so the caller can handle it
            }
        }
    }


    protected static function afterSave($record): void
    {
        if ($record->wasChanged('google_sheet_id')) {
            static::syncSheet($record);
        }
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_products') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_products') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_products') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_products') ?? false;
    }
}
