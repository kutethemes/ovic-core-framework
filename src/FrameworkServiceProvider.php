<?php

namespace Ovic\Framework;

use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class FrameworkServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register factories.
     *
     * @param  string  $path
     *
     * @return void
     */
    protected function loadFactoriesFrom( $path )
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }

    /**
     * Register all modules.
     */
    public function register()
    {
        $this->app['router']->aliasMiddleware('permission', Permission::class);
    }

    /**
     * Booting the package.
     */
    public function boot( Request $request )
    {
        /* Load Factories */
        $this->loadFactoriesFrom(__DIR__.'/../database/factories');

        /* Load Migrations */
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        /* Load Routes */
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        /* Load Language */
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'ovic');

        /* Load View */
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ovic');

        /* Publishes */
        $this->publishes_assets();
        $this->publishes_views();
        $this->publishes_lang();
        $this->publishes_auth();
    }

    public function publishes_assets()
    {
        $this->publishes(
            [
                __DIR__.'/../assets/' => public_path(),
            ],
            'ovic-assets'
        );
    }

    public function publishes_views()
    {
        $this->publishes(
            [
                __DIR__.'/../resources/views/' => resource_path('views'),
            ],
            'ovic-views'
        );
    }

    public function publishes_lang()
    {
        $this->publishes(
            [
                __DIR__.'/../resources/lang' => resource_path('lang/ovic-core/framework'),
            ],
            'ovic-lang'
        );
    }

    public function publishes_auth()
    {
        $this->publishes(
            [
                __DIR__.'/../resources/views/backend/auth' => resource_path('views/auth'),
            ],
            'ovic-auth'
        );
    }
}
