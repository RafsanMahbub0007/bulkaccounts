<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->isProduction() || str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        Gate::before(fn ($user) => $user->hasRole('admin') ? true : null);

        if (Schema::hasTable('permissions')) {
            foreach (Permission::query()->pluck('name') as $permission) {
                Gate::define($permission, fn ($user) => $user->hasPermissionTo($permission));
            }
        }
    }
}
