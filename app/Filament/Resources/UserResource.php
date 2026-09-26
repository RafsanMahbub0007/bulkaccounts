<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Schema;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 50;

    public static function form(Form $form): Form
    {
        $detailsSection = [
            TextInput::make('name')
                ->label('Full Name')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->label('Email Address')
                ->email()
                ->required()
                ->maxLength(255),
        ];

        if (Schema::hasColumn('users', 'phone')) {
            $detailsSection[] =
                TextInput::make('phone')
                    ->label('Phone Number')
                    ->maxLength(255);
        }

        if (Schema::hasColumn('users', 'email_password')) {
            $detailsSection[] =
                TextInput::make('email_password')
                    ->label('Email Password')
                    ->helperText('Use only if your workflow stores a separate email login password.')
                    ->maxLength(255);
        }

        return $form->schema([
            Section::make('User Details')
                ->description('Enter the main account details for this user.')
                ->schema($detailsSection)
                ->columns(2),
            Section::make('Roles and Access')
                ->description('Assign one or more roles to control backend access.')
                ->schema([
                    CheckboxList::make('roles')
                        ->relationship('roles', 'name')
                        ->label('Assigned Roles')
                        ->columns(2)
                        ->helperText('Users can have multiple roles.'),
                ]),
            Section::make('Password')
                ->description('Set a password when creating a new user or update it later if needed.')
                ->schema([
                    TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->label('New Password')
                        ->minLength(8)
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->dehydrated(fn (?string $state): bool => filled($state)),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $columns = [
            TextColumn::make('name')
                ->searchable()
                ->sortable(),

            TextColumn::make('email')
                ->searchable()
                ->sortable()
                ->copyable(),

            TextColumn::make('roles.name')
                ->label('Roles')
                ->badge()
                ->separator(', '),

            TextColumn::make('email_password')
                ->label('Email Password')
                ->default('Not stored'),

            IconColumn::make('email_verified_at')
                ->label('Email Verified')
                ->boolean()
                ->getStateUsing(fn (User $record): bool => filled($record->email_verified_at)),

            TextColumn::make('password')
                ->copyable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];

        if (Schema::hasColumn('users', 'phone')) {
            array_splice($columns, 1, 0, [
                TextColumn::make('phone')
                    ->label('Phone Number')
                    ->searchable()
                    ->copyable(),
            ]);
        }

        return $table
            ->poll('5s')
            ->columns($columns)
            ->actions([
                Tables\Actions\Action::make('set_password')
                    ->label('Set Password')
                    ->icon('heroicon-o-key')
                    ->form([
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->required(),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->forceFill([
                            'password' => $data['password'],
                        ])->save();

                        Notification::make()
                            ->title('Password updated')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_users') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo('manage_users') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_users') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermissionTo('manage_users') ?? false;
    }
}
