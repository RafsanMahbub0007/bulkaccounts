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
use Filament\Forms\Components\RichEditor;
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

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Sub Categories';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Sub Category Details')
                    ->description('Choose the parent category and enter the core sub category details.')
                    ->schema([
                        Select::make('category_id')
                            ->label('Parent Category')
                            ->relationship('category', 'name')
                            ->required(),
                        TextInput::make('name')
                            ->label('Sub Category Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter the sub category name'),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->unique(SubCategory::class, 'slug', ignoreRecord: true)
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter the sub category URL slug'),
                        TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->label('Display Order'),
                        Toggle::make('is_active')
                            ->label('Active Status')
                            ->default(false),
                        FileUpload::make('image')
                            ->label('Sub Category Image')
                            ->image()
                            ->directory('subcategories'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('SEO Information')
                    ->description('Add search metadata and page content for this sub category.')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255),
                        TagsInput::make('keywords')
                            ->label('Meta Keywords')
                            ->placeholder('Add keywords...')
                            ->splitKeys([','])
                            ->afterStateHydrated(function (TagsInput $component, $state) {
                                $component->state($state ? explode(',', $state) : []);
                            })
                            ->dehydrateStateUsing(fn($state) => is_array($state) ? implode(',', $state) : $state)
                            ->nullable()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Meta Description')
                            ->nullable()
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('Detailed Content / Description for user panel')
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('category.name', 'asc')
            ->defaultSort('order', 'asc')

            // Group by category
            ->groups([
                Tables\Grouping\Group::make('category.name')
                    ->label('Category Name => ')
                    ->collapsible(),
            ])
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('category.name') // ← This is the fix
                    ->label('Category Name')
                    ->sortable()
                    ->searchable(),
                ImageColumn::make('image')
                    ->label('Image')
                    ->getStateUsing(fn($record) => $record->image ? image_path($record->image) : null)
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

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_subcategories') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_subcategories') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_subcategories') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_subcategories') ?? false;
    }
}
