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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';

    /* ================= FORM ================= */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\Select::make('category_id')
                ->relationship('category', 'name')
                ->required()
                ->reactive(),
            Forms\Components\Select::make('subcategory_id')
                ->options(fn($get) => $get('category_id')
                    ? SubCategory::where('category_id', $get('category_id'))->pluck('name', 'id')
                    : [])
                ->required()
                ->disabled(fn($get) => !$get('category_id')),
            Forms\Components\CheckboxList::make('feature_ids')
                ->options(ProductFeature::pluck('name', 'id'))
                ->columns(2),
            Forms\Components\TextInput::make('purchase_price')->numeric()->required(),
            Forms\Components\TextInput::make('selling_price')->numeric()->required(),
            Forms\Components\TextInput::make('min_order_qty')->numeric()->default(10),
            Forms\Components\Textarea::make('description'),
            Forms\Components\RichEditor::make('content')->columnSpanFull(),
            Forms\Components\TextInput::make('google_sheet_id')
                ->label('Google Sheet ID')
                ->hint('Enter the ID from the sheet URL, e.g., 1AbCXYZ...')
                ->required(),
        ]);
    }

    /* ================= TABLE ================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('stock')->badge(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('openSheet')
                    ->label('Open Sheet')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn($record) => "https://docs.google.com/spreadsheets/d/{$record->google_sheet_id}")
                    ->openUrlInNewTab()
                    ->visible(fn($record) => filled($record->google_sheet_id)),

                Tables\Actions\Action::make('syncSheet')
                    ->label('Sync Sheet')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->action(fn($record) => static::syncSheet($record))
                    ->disabled(fn($record) => blank($record->google_sheet_id)),
            ]);
    }

    /* ================= SYNC ENGINE (Multi-Tab: unsold/sold/banned) ================= */
    public static function syncSheet(Product $product): void
    {
        try {
            if (!$product->google_sheet_id) {
                throw new \Exception('Google Sheet ID missing');
            }

            $client = new \Google\Client();
            $client->setApplicationName('Product Sheet Dynamic Multi-Tab Sync');
            $client->setScopes([\Google\Service\Sheets::SPREADSHEETS_READONLY]);
            $client->setAuthConfig(config('services.google.credentials'));

            $service = new \Google\Service\Sheets($client);

            // 🔹 Step 1: Get all sheets dynamically
            $spreadsheet = $service->spreadsheets->get($product->google_sheet_id);
            $sheets = $spreadsheet->getSheets();

            if (empty($sheets)) {
                throw new \Exception('No sheets found in the spreadsheet');
            }

            $processed = [];
            $totalCount = 0;

            DB::beginTransaction();

            foreach ($sheets as $sheet) {
                $tabName = $sheet->getProperties()->getTitle();
                $range = $tabName . '!A:Z';

                try {
                    $response = $service->spreadsheets_values->get($product->google_sheet_id, $range);
                } catch (\Throwable $e) {
                    continue; // skip missing or empty tabs
                }

                $rows = $response->getValues();
                if (!$rows || count($rows) < 2) continue;

                $headers = array_map('trim', array_shift($rows));
                if (!in_array('email', $headers)) continue; // skip if email column missing

                foreach ($rows as $row) {
                    if (count(array_filter($row)) === 0) continue;

                    $data = array_combine($headers, $row);
                    if (empty($data['email'])) continue;

                    $email = strtolower(trim($data['email']));
                    if (isset($processed[$email])) continue;
                    $processed[$email] = true;

                    $existing = ProductAccount::where('product_id', $product->id)
                        ->where('email', $email)
                        ->first();

                    // ❌ Never overwrite sold/banned if already marked
                    if ($existing && in_array($existing->status, ['sold', 'banned'])) {
                        continue;
                    }

                    if ($existing) {
                        $existing->update([
                            'meta' => $data,
                            'status' => strtolower($tabName), // use tab name as status
                        ]);
                    } else {
                        ProductAccount::create([
                            'product_id' => $product->id,
                            'email' => $email,
                            'status' => strtolower($tabName),
                            'meta' => $data,
                        ]);
                    }

                    $totalCount++;
                }
            }

            // 🔹 Update product stock (unsold only)
            $product->update([
                'stock' => ProductAccount::where('product_id', $product->id)
                    ->where('status', 'unsold')
                    ->count(),
            ]);

            DB::commit();

            Notification::make()
                ->title('Sheet synced')
                ->body("{$totalCount} accounts synced across all tabs")
                ->success()
                ->send();
        } catch (\Throwable $e) {
            DB::rollBack();

            Notification::make()
                ->title('Sync failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }


    protected static function afterSave($record): void
    {
        if ($record->google_sheet_id) {
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
}
