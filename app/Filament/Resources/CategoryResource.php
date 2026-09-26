<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
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

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Categories';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Category Details')
                    ->description('Set the basic category information used in the store.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Category Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter the category name'),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->unique(Category::class, 'slug', ignoreRecord: true)
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter the category URL slug'),
                        TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->label('Display Order'),
                        Toggle::make('is_active')
                            ->label('Active Status')
                            ->default(false),
                        FileUpload::make('image')
                            ->label('Category Image')
                            ->image()
                            ->directory('categories'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('SEO Information')
                    ->description('Add optional SEO data for this category page.')
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
                            ->label('Short Description')
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
            ->defaultSort('order', 'asc')
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_categories') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_categories') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_categories') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_categories') ?? false;
    }
}
