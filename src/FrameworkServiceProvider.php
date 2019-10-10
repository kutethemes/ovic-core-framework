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
	 * Register all modules.
	 */
	public function register()
	{
		include __DIR__ . '/routes/web.php';
		$this->app->make( 'Ovic\Framework\FrameworkController' );
	}

	/**
	 * Booting the package.
	 */
	public function boot()
	{
		/* Load View */
		$this->loadViewsFrom( __DIR__ . '/../resources/views', 'framework' );
		/* Publishes */
		$this->publishes(
			[
				__DIR__ . '/../public/' => public_path(),
			],
			'ovic-assets'
		);
		$this->publishes(
			[
				__DIR__ . '/../resources/views/' => resource_path( 'views' ),
			],
			'ovic-views'
		);
		$this->publishes(
			[
				__DIR__ . '/../config/config.php' => config_path( 'ovic.php' ),
			],
			'ovic-config'
		);
	}
}
