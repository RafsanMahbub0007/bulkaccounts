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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';

    /* ================= FORM ================= */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),

            Forms\Components\TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\Select::make('category_id')
                ->relationship('category', 'name')
                ->required()
                ->reactive(),

            Forms\Components\Select::make('subcategory_id')
                ->options(fn ($get) =>
                    $get('category_id')
                        ? SubCategory::where('category_id', $get('category_id'))->pluck('name', 'id')
                        : []
                )
                ->required()
                ->disabled(fn ($get) => !$get('category_id')),

            Forms\Components\CheckboxList::make('feature_ids')
                ->options(ProductFeature::pluck('name', 'id'))
                ->columns(2),

            Forms\Components\TextInput::make('purchase_price')->numeric()->required(),
            Forms\Components\TextInput::make('selling_price')->numeric()->required(),
            Forms\Components\TextInput::make('min_order_qty')->numeric()->default(10),

            Forms\Components\Textarea::make('description'),
            Forms\Components\RichEditor::make('content')->columnSpanFull(),

            Forms\Components\TextInput::make('google_sheet_url')
                ->label('Google Sheet CSV URL')
                ->url(),
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
                    ->url(fn ($record) => $record->google_sheet_url)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => filled($record->google_sheet_url)),

                Tables\Actions\Action::make('syncSheet')
                    ->label('Sync Sheet')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->action(fn ($record) => static::syncSheet($record))
                    ->disabled(fn ($record) => blank($record->google_sheet_url)),
            ]);
    }

    /* ================= SYNC ENGINE ================= */
    public static function syncSheet(Product $product): void
    {
        try {
            if (!$product->google_sheet_url) {
                throw new \Exception('Google Sheet URL missing');
            }

            $response = Http::get($product->google_sheet_url);
            if (!$response->ok()) {
                throw new \Exception('Failed to fetch Google Sheet');
            }

            $csv  = trim($response->body());
            $hash = md5($csv);

            // 🚀 FAST EXIT IF UNCHANGED
            if ($product->sheet_hash === $hash) {
                return;
            }

            $rows = array_map('str_getcsv', explode("\n", $csv));
            if (count($rows) < 2) {
                throw new \Exception('Sheet has no data');
            }

            $headers = array_map('trim', array_shift($rows));
            if (!in_array('email', $headers)) {
                throw new \Exception('Email column missing');
            }

            DB::beginTransaction();

            $emails = [];
            $count  = 0;

            foreach ($rows as $row) {
                if (count(array_filter($row)) === 0) continue;

                $data = array_combine($headers, $row);
                if (empty($data['email'])) continue;

                $email = strtolower(trim($data['email']));
                if (isset($emails[$email])) continue; // ❌ duplicate CSV row

                $emails[$email] = true;

                $existing = ProductAccount::where('product_id', $product->id)
                    ->where('email', $email)
                    ->first();

                // ❌ NEVER resurrect sold/banned accounts
                if ($existing && in_array($existing->status, ['sold', 'banned'])) {
                    continue;
                }

                ProductAccount::updateOrCreate(
                    ['product_id' => $product->id, 'email' => $email],
                    [
                        'status' => 'unsold',
                        'meta'   => $data,
                    ]
                );

                $count++;
            }

            // ❌ Only delete UNSOLD missing accounts
            ProductAccount::where('product_id', $product->id)
                ->where('status', 'unsold')
                ->whereNotIn('email', array_keys($emails))
                ->delete();

            $product->update([
                'sheet_hash' => $hash,
                'stock' => ProductAccount::where('product_id', $product->id)
                    ->where('status', 'unsold')
                    ->count(),
                'sheet_meta' => [
                    'headers'   => $headers,
                    'row_count'=> $count,
                    'synced_at'=> now(),
                ],
            ]);

            DB::commit();

            Notification::make()
                ->title('Sheet synced')
                ->body("{$count} accounts synced")
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
        if ($record->google_sheet_url) {
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
