<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repository bindings
        $this->app->bind(
            \App\Domain\Admin\Repositories\ContentRepository::class,
            \App\Infra\Repositories\Admin\ContentDatabaseRepository::class
        );

        $this->app->bind(
            \App\Domain\Admin\Repositories\ContactRepository::class,
            \App\Infra\Repositories\Admin\ContactDatabaseRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
