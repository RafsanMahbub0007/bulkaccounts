<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Profile';

    protected static string $view = 'filament.pages.profile';

    public ?array $data = [];

    public function mount(): void
    {
        /** @var User $user */
        $user = auth()->user();

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => Schema::hasColumn('users', 'phone') ? $user->phone : null,
            'profile_photo_path' => $user->profile_photo_path,
        ]);
    }

    public function form(Form $form): Form
    {
        $schema = [
            FileUpload::make('profile_photo_path')
                ->label('Profile Photo')
                ->image()
                ->avatar()
                ->disk('public')
                ->directory('profile-photos')
                ->getUploadedFileUsing(function (FileUpload $component, string $file): ?array {
                    $storage = Storage::disk('public');

                    if (! $storage->exists($file)) {
                        return null;
                    }

                    return [
                        'name' => basename($file),
                        'size' => $storage->size($file),
                        'type' => $storage->mimeType($file),
                        'url' => '/storage/' . ltrim($file, '/'),
                    ];
                })
                ->imageEditor()
                ->nullable()
                ->columnSpanFull(),
            TextInput::make('name')
                ->label('Full Name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->label('Email Address')
                ->email()
                ->required()
                ->maxLength(255)
                ->rule(fn () => Rule::unique('users', 'email')->ignore(auth()->id())),
            TextInput::make('phone')
                ->label('Phone Number')
                ->maxLength(255)
                ->visible(fn () => Schema::hasColumn('users', 'phone')),
            TextInput::make('password')
                ->label('New Password')
                ->password()
                ->revealable()
                ->confirmed()
                ->rule(Password::default())
                ->dehydrated(fn (?string $state): bool => filled($state)),
            TextInput::make('password_confirmation')
                ->label('Confirm New Password')
                ->password()
                ->revealable()
                ->dehydrated(false),
        ];

        return $form
            ->schema($schema)
            ->statePath('data')
            ->columns(2);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        /** @var User $user */
        $user = auth()->user();

        $payload = [
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'profile_photo_path' => $data['profile_photo_path'] ?? $user->profile_photo_path,
        ];

        if (Schema::hasColumn('users', 'phone')) {
            $payload['phone'] = $data['phone'] ?? null;
        }

        if (filled($data['password'] ?? null)) {
            $payload['password'] = $data['password'];
        }

        $user->forceFill($payload)->save();

        Notification::make()
            ->title('Profile updated')
            ->success()
            ->send();
    }
}
