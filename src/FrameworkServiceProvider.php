<?php

namespace Ovic\Framework;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class FrameworkServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register all modules.
     */
    public function register()
    {
        $this->app->register(FrameworkAuthServiceProvider::class);
        $this->app->register(FrameworkEventServiceProvider::class);
    }

    /**
     * Booting the package.
     */
    public function boot( Request $request )
    {
        /* Load Menu */
        view()->composer('*', function ( $view ) {
            $view->with([
                'primary_menu' => [
                    'left' => Ucases::Menus('left', true),
                    'top'  => Ucases::Menus('top', true),
                ]
            ]);
        });

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
