<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\ProductAccount;
use App\Models\ProductFeature;
use App\Models\SubCategory;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
                TextInput::make('purchase_price')->numeric()->required(),
                TextInput::make('selling_price')->numeric()->required(),
                TextInput::make('stock')->numeric()->default(0)->disabled()->dehydrated(false),
                TextInput::make('min_order_qty')->numeric()->default(10),
                TagsInput::make('keywords')->placeholder('Add keywords...')
                    ->splitKeys([','])
                    ->dehydrateStateUsing(fn($state) => is_array($state) ? implode(',', $state) : $state)
                    ->nullable(),
                Textarea::make('description')->label('Product Description')->nullable(),
                RichEditor::make('content')->label('Product Content')->columnSpanFull()->nullable(),

                FileUpload::make('accounts_excel')
                    ->label('Upload Excel (.xls/.xlsx)')
                    ->acceptedFileTypes([
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
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
                TextColumn::make('category.name')->label('Category')->sortable()->searchable(),
                TextColumn::make('subcategory.name')->label('Sub-Category')->sortable()->searchable(),
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
    public static function getHeaderActions(): array
    {
        return [
            \Filament\Pages\Actions\Action::make('downloadExample')
                ->label('Download Example File')
                ->icon('heroicon-o-download')
                ->button()
                ->color('primary')
                ->action(function () {
                    $headers = [
                        'full_name',
                        'email_account',
                        'email_password',
                        'account_password',
                        'uid',
                        'recovery_email',
                        'profile_link',
                        'create_date',
                        'download_link',
                        '2fa_code',
                        'username',
                        'location',
                        'connection',
                        'karma',
                        'followers',
                        'friends',
                        'phone_number',
                        'plan_type',
                        'card_number',
                        'expiry_date',
                        'cvv_code',
                        'card_type',
                        'balance',
                        'storage_capacity',
                    ];

                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->fromArray($headers, null, 'A1');

                    $writer = new Xlsx($spreadsheet);

                    $response = new StreamedResponse(function () use ($writer) {
                        $writer->save('php://output');
                    });

                    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    $response->headers->set('Content-Disposition', 'attachment; filename="product_accounts_template.xlsx"');

                    $response->send();
                    exit;
                }),
        ];
    }


public static function processExcelUpload(Product $product, bool $force = false): array
{
    $summary = [
        'status' => 'ok',
        'added' => 0,
        'updated' => 0,
        'deleted' => 0,
        'skipped_file_duplicate' => 0,
        'skipped_other_product' => 0,
        'skipped_no_email' => 0,
        'errors' => [],
        'skipped_rows' => [],
    ];

    try {
        if (!$product->accounts_excel) {
            $summary['status'] = 'no_file';
            $summary['errors'][] = 'No Excel file attached.';
            return $summary;
        }

        $filePath = $product->accounts_excel;

        if (!$force && $product->accounts_excel_processed_file === $filePath) {
            $summary['status'] = 'already_processed';
            $summary['errors'][] = "This file was already imported.";
            return $summary;
        }

        if (!Storage::disk('public')->exists($filePath)) {
            $summary['status'] = 'file_not_found';
            $summary['errors'][] = "File not found: {$filePath}";
            return $summary;
        }

        $fullPath = Storage::disk('public')->path($filePath);

        $spreadsheet = IOFactory::load($fullPath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, false, false);

        if (empty($rows) || count($rows) < 2) {
            $summary['status'] = 'empty';
            $summary['errors'][] = 'Excel file has no data rows.';
            return $summary;
        }

        // Headers
        $rawHeaders = array_map('trim', array_shift($rows));

        $headers = [];
        foreach ($rawHeaders as $index => $h) {
            $h = $h === '' ? 'column_' . ($index + 1) : (string) $h;
            $base = $h;
            $suffix = 1;
            while (in_array($h, $headers, true)) {
                $h = "{$base}_{$suffix}";
                $suffix++;
            }
            $headers[] = $h;
        }

        // Find email column
        $emailHeaderKey = null;
        foreach ($headers as $h) {
            if (stripos($h, 'email') !== false) {
                $emailHeaderKey = $h;
                break;
            }
        }
        if (!$emailHeaderKey) {
            $emailHeaderKey = $headers[0] ?? null;
        }
        if (!$emailHeaderKey) {
            $summary['status'] = 'invalid_headers';
            $summary['errors'][] = 'Unable to determine email column.';
            return $summary;
        }

        $seenEmails = [];
        $rowNumber = 2;

        foreach ($rows as $row) {

            if (count(array_filter($row, fn($c) => trim((string)$c) !== '')) === 0) {
                break;
            }

            $values = array_values($row);
            $values = array_map(fn($v) => trim((string)($v ?? '')), $values);
            if (count($values) < count($headers)) {
                $values = array_pad($values, count($headers), '');
            }

            $data = array_combine($headers, $values);

            $email = trim($data[$emailHeaderKey] ?? '');
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $summary['skipped_no_email']++;
                $summary['skipped_rows'][] = [
                    'row' => $rowNumber,
                    'reason' => 'invalid_email',
                    'data' => $data,
                ];
                $rowNumber++;
                continue;
            }

            $emailLower = strtolower($email);

            if (in_array($emailLower, $seenEmails, true)) {
                $summary['skipped_file_duplicate']++;
                $summary['skipped_rows'][] = [
                    'row' => $rowNumber,
                    'reason' => 'duplicate_in_file',
                    'email' => $email,
                ];
                $rowNumber++;
                continue;
            }
            $seenEmails[] = $emailLower;

            $existingOtherProduct = ProductAccount::where('email', $email)->first();
            if ($existingOtherProduct && $existingOtherProduct->product_id !== $product->id) {
                $summary['skipped_other_product']++;
                $summary['skipped_rows'][] = [
                    'row' => $rowNumber,
                    'reason' => 'belongs_to_other_product',
                    'email' => $email,
                ];
                $rowNumber++;
                continue;
            }

            // Build meta
            $meta = [];
            foreach ($data as $k => $v) {
                if ($k !== $emailHeaderKey && $v !== '') {
                    $meta[$k] = $v;
                }
            }

            // Update or create
            $existing = ProductAccount::where('product_id', $product->id)
                ->where('email', $email)
                ->first();

            if ($existing) {
                $existing->update(['meta' => $meta]);
                $summary['updated']++;
            } else {
                ProductAccount::create([
                    'product_id' => $product->id,
                    'email' => $email,
                    'meta' => $meta,
                ]);
                $summary['added']++;
            }

            $rowNumber++;
        }

        // DELETE ACCOUNTS NOT IN FILE (REPLACE mode)
        $existingAccounts = ProductAccount::where('product_id', $product->id)->get();

        $idsToDelete = [];
        foreach ($existingAccounts as $acct) {
            if (!in_array(strtolower($acct->email), $seenEmails, true)) {
                $idsToDelete[] = $acct->id;
            }
        }

        if (!empty($idsToDelete)) {
            $summary['deleted'] = count($idsToDelete);
            ProductAccount::whereIn('id', $idsToDelete)->delete();
        }

        // Update product stock
        $product->stock = ProductAccount::where('product_id', $product->id)->count();

        $product->save();

        return $summary;

    } catch (\Throwable $e) {

        $summary['status'] = 'error';
        $summary['errors'][] = $e->getMessage();

        try {
            $product->last_import_summary = $summary;
            $product->accounts_excel_processed_at = now();
            $product->save();
        } catch (\Throwable $_) {}

        return $summary;
    }
}

}
