<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Page Setups';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Post Details')
                    ->description('Enter the main blog post information used on the frontend.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Post Title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Post::class, 'slug', ignoreRecord: true),
                        Select::make('author_id')
                            ->label('Author')
                            ->relationship('author', 'name')
                            ->required(),
                        Toggle::make('published')
                            ->label('Published Status')
                            ->default(false),
                        FileUpload::make('image')
                            ->label('Featured Image')
                            ->image()
                            ->directory('post-images')
                            ->nullable(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('SEO and Content')
                    ->description('Add the SEO title, short description, and full post content.')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Short Description')
                            ->maxLength(500)
                            ->nullable()
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->columnSpanFull()
                            ->label('Post Content')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('author.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('published')
                    ->label('Published')
                    ->icon(fn (bool $state): string => $state
                        ? 'heroicon-o-check-circle'
                        : 'heroicon-o-x-circle')
                    ->colors([
                        'success' => fn (bool $state): bool => $state,
                        'danger' => fn (bool $state): bool => ! $state,
                    ]),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Created At')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Last Updated')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyPermission(['create_post', 'edit_post', 'delete_post']) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo('create_post') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermissionTo('edit_post') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermissionTo('delete_post') ?? false;
    }
}
