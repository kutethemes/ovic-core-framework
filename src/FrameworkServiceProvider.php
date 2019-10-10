<?php
namespace Ovic\Framework;

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
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		include __DIR__ . '/routes/web.php';
		$this->app->make( 'Ovic\Framework\FrameworkController' );
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		/* Load View */
		$this->loadViewsFrom( __DIR__ . '/../resources/views', 'framework' );
		/* Publishes */
		$this->publishes(
			[
				__DIR__ . '/../public/'              => public_path(),
				__DIR__ . '/../resources/views/'     => resource_path( 'views/' ),
				__DIR__ . '/../config/framework.php' => config_path( 'framework.php' ),
			]
		);
	}
}
