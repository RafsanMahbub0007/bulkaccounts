<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    public function givePermissionTo(array|string $permissions): static
    {
        $names = collect(is_array($permissions) ? $permissions : [$permissions])
            ->map(fn ($permission) => $permission instanceof Permission ? $permission->name : $permission)
            ->all();

        $permissionIds = Permission::query()
            ->whereIn('name', $names)
            ->pluck('id')
            ->all();

        $this->permissions()->syncWithoutDetaching($permissionIds);

        return $this;
    }

    public function syncPermissions(array|string $permissions): static
    {
        $names = collect(is_array($permissions) ? $permissions : [$permissions])
            ->map(fn ($permission) => $permission instanceof Permission ? $permission->name : $permission)
            ->all();

        $permissionIds = Permission::query()
            ->whereIn('name', $names)
            ->pluck('id')
            ->all();

        $this->permissions()->sync($permissionIds);

        return $this;
    }

    public function hasPermissionTo(string $permission): bool
    {
        return $this->permissions()->where('name', $permission)->exists();
    }
}
