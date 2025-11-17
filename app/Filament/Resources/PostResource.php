<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
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
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(Post::class, 'slug', ignoreRecord: true),

                FileUpload::make('image')
                    ->label('Featured Image')
                    ->image()
                    ->directory('post-images')
                    ->nullable(),

                Textarea::make('description')
                    ->maxLength(500)
                    ->nullable(),

                RichEditor::make('content')
                    ->columnSpanFull()
                    ->label('Post Content')
                    ->required(),

                Select::make('author_id')
                    ->columnSpanFull()
                    ->label('Author')
                    ->relationship('author', 'name')
                    ->required(),

                Toggle::make('published')
                    ->label('Publish')
                    ->default(false),
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
                    ->icon(fn(bool $state): string => $state
                        ? 'heroicon-o-check-circle'
                        : 'heroicon-o-x-circle')
                    ->colors([
                        'success' => fn(bool $state): bool => $state,
                        'danger' => fn(bool $state): bool => !$state,
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
}
