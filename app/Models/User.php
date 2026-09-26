<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'email_password',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Get all the orders placed by the user
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    // Get all payments made by the user
    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    // Get all posts made by the user
    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function getAllPermissions(): array
    {
        if (! Schema::hasTable('permissions') || ! Schema::hasTable('permission_role') || ! Schema::hasTable('role_user')) {
            return [];
        }

        return $this->roles()
            ->with('permissions:id,name')
            ->get()
            ->flatMap(fn (Role $role) => $role->permissions->pluck('name'))
            ->unique()
            ->values()
            ->all();
    }

    public function hasRole(array|string $roles): bool
    {
        $roles = collect(is_array($roles) ? $roles : [$roles])->map(fn ($role) => strtolower($role));

        if ($roles->contains('admin') && (int) $this->role_id === 1) {
            return true;
        }

        if ($roles->contains('seller') && (int) $this->role_id === 3) {
            return true;
        }

        if ($roles->contains('user') && (int) $this->role_id === 2) {
            return true;
        }

        if (! Schema::hasTable('roles') || ! Schema::hasTable('role_user')) {
            return false;
        }

        return $this->roles()
            ->whereIn('name', $roles->all())
            ->exists();
    }

    public function hasAnyRole(array|string $roles): bool
    {
        return $this->hasRole($roles);
    }

    public function hasPermissionTo(string $permission): bool
    {
        if ($this->hasRole('admin')) {
            return true;
        }

        if (! Schema::hasTable('permissions') || ! Schema::hasTable('permission_role') || ! Schema::hasTable('role_user')) {
            return false;
        }

        return $this->roles()
            ->whereHas('permissions', fn ($query) => $query->where('name', $permission))
            ->exists();
    }

    public function hasAnyPermission(array|string $permissions): bool
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];

        foreach ($permissions as $permission) {
            if ($this->hasPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    public function assignRole(array|string $roles): static
    {
        $roles = collect(is_array($roles) ? $roles : [$roles]);

        if (! Schema::hasTable('roles') || ! Schema::hasTable('role_user')) {
            return $this;
        }

        $roleIds = Role::query()
            ->whereIn('name', $roles->all())
            ->pluck('id')
            ->all();

        $this->roles()->syncWithoutDetaching($roleIds);
        $this->syncLegacyRoleIdFromRoles();

        return $this;
    }

    public function syncRoles(array|string $roles): static
    {
        $roles = collect(is_array($roles) ? $roles : [$roles]);

        if (! Schema::hasTable('roles') || ! Schema::hasTable('role_user')) {
            return $this;
        }

        $roleIds = Role::query()
            ->whereIn('name', $roles->all())
            ->pluck('id')
            ->all();

        $this->roles()->sync($roleIds);
        $this->syncLegacyRoleIdFromRoles();

        return $this;
    }

    public function syncLegacyRoleIdFromRoles(): void
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('role_user')) {
            return;
        }

        $roleNames = $this->roles()->pluck('name');

        $legacyRoleId = match (true) {
            $roleNames->contains('admin') => 1,
            $roleNames->contains('seller') => 3,
            default => 2,
        };

        if ((int) $this->role_id !== $legacyRoleId) {
            $this->forceFill(['role_id' => $legacyRoleId])->saveQuietly();
        }
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasPermissionTo('access_admin_panel') || $this->hasRole('admin');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (blank($this->profile_photo_path)) {
            return null;
        }

        if (str_starts_with($this->profile_photo_path, 'http://') || str_starts_with($this->profile_photo_path, 'https://')) {
            return $this->profile_photo_path;
        }

        return Storage::disk('public')->exists($this->profile_photo_path)
            ? '/storage/' . ltrim($this->profile_photo_path, '/')
            : null;
    }
}
