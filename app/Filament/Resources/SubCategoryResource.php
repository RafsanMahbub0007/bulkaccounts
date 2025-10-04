<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubCategoryResource\Pages;
use App\Filament\Resources\SubCategoryResource\RelationManagers;
use App\Models\SubCategory;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubCategoryResource extends Resource
{
    protected static ?string $model = SubCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Sub-Categories';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->label('Select Category')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter Sub-Category name'),
                TextInput::make('slug')
                    ->unique(SubCategory::class, 'slug', ignoreRecord: true)
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter Sub-Category slug'),
                TagsInput::make('keywords')
                    ->placeholder('Add keywords...')
                    ->dehydrateStateUsing(fn($state) => is_array($state) ? implode(',', $state) : $state)
                    ->nullable(),
                Textarea::make('description')
                    ->nullable(),
                FileUpload::make('image')
                    ->label('Sub-Category Image')
                    ->image()
                    ->directory('subcategories'),
                TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->label('Display Order'),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('category.name') // ← This is the fix
                    ->label('Category Name')
                    ->sortable()
                    ->searchable(),
                ImageColumn::make('image')
                ->label('Image')
                ->getStateUsing(fn ($record) => $record->image ? asset('storage/' . $record->image) : null)
                ->square(),
                TextColumn::make('order')->sortable()->label('Order'),
                IconColumn::make('is_active')
                    ->label('Status')
                    ->icon(fn(bool $state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->colors([
                        'success' => fn(bool $state): bool => $state,
                        'danger' => fn(bool $state): bool => !$state,
                    ]),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Created At'),
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
            'index' => Pages\ListSubCategories::route('/'),
            'create' => Pages\CreateSubCategory::route('/create'),
            'edit' => Pages\EditSubCategory::route('/{record}/edit'),
        ];
    }
}
