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

        $this->app->bind(
            \App\Domain\Admin\Repositories\SectionSettingRepository::class,
            \App\Infra\Repositories\Admin\SectionSettingDatabaseRepository::class
        );

        $this->app->bind(
            \App\Domain\Admin\Repositories\ProjectRepository::class,
            \App\Infra\Repositories\Admin\ProjectDatabaseRepository::class
        );

        $this->app->bind(
            \App\Domain\Portfolio\Repositories\ProjectRepository::class,
            \App\Infra\Repositories\Portfolio\ProjectDatabaseRepository::class
        );

        $this->app->bind(
            \App\Domain\Portfolio\Repositories\PageContentRepositoryInterface::class,
            \App\Infra\Repositories\Portfolio\PageContentEloquentRepository::class
        );

        // Service bindings
        $this->app->bind(
            \App\Domain\Admin\Services\SectionVisibilityServiceInterface::class,
            \App\Application\Portfolio\Services\SectionVisibilityService::class
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
