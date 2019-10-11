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
		/* Load Language */
		$this->loadTranslationsFrom( __DIR__ . '/../resources/lang', 'ovic' );

		/* Load View */
		$this->loadViewsFrom( __DIR__ . '/../resources/views', 'ovic' );

		/* Publishes */
		$this->publishes(
			[
				__DIR__ . '/../assets/' => public_path(),
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
				__DIR__ . '/../resources/lang' => resource_path( 'lang/ovic-core/framework' ),
			],
			'ovic-lang'
		);
	}
}
