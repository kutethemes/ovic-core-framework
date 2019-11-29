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
        /* Load Schedule */
        $this->app->booted(function () {
            $this->app->make(FrameworkSchedule::class);
        });
        /* Load Service Provider */
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
            $menu_left = [];
            $menu_top  = [];
            if ( Ucases::hasTable() ) {
                $menu_left = Ucases::PrimaryMenu('left');
                $menu_top  = Ucases::PrimaryMenu('top');
            }
            $view->with([
                'primary_menu' => [
                    'left' => $menu_left,
                    'top'  => $menu_top,
                ]
            ]);
        });

        /* Load Config */
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'ovic');

        /* Load Migrations */
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        /* Load Routes */
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        /* Load Language */
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'ovic');

        /* Load View */
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ovic');

        /* Publishes */
        $this->publishes_config();
        $this->publishes_assets();
        $this->publishes_views();
        $this->publishes_lang();
        $this->publishes_auth();
    }

    public function publishes_config()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('ovic.php')
        ], 'ovic-config');
    }

    public function publishes_assets()
    {
        $this->publishes(
            [
                __DIR__ . '/../assets/' => public_path(),
            ],
            'ovic-assets'
        );
    }

    public function publishes_views()
    {
        $this->publishes(
            [
                __DIR__ . '/../resources/views/' => resource_path('views'),
            ],
            'ovic-views'
        );
    }

    public function publishes_lang()
    {
        $this->publishes(
            [
                __DIR__ . '/../resources/lang' => resource_path('lang/ovic-core/framework'),
            ],
            'ovic-lang'
        );
    }

    public function publishes_auth()
    {
        $this->publishes(
            [
                __DIR__ . '/../resources/views/auth' => resource_path('views/auth'),
            ],
            'ovic-auth'
        );
    }
}
