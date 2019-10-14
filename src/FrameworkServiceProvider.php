<?php
namespace Ovic\Framework;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
		$this->loadRoutesFrom( __DIR__ . '/routes/web.php' );
	}

	/**
	 * Booting the package.
	 */
	public function boot( Request $request )
	{
		/* compose all the views */
		view()->composer( 'ovic::backend.dashboard.app',
			function ( $view ) {
				$currentUser = array(
					'id'    => '',
					'name'  => '',
					'email' => '',
				);
				if ( Auth::check() ) {
					$user        = Auth::user();
					$currentUser = array(
						'id'    => $user->id,
						'name'  => $user->name,
						'email' => $user->email,
					);
					$view->with( 'currentUser', $currentUser );
				} else {
					$view->with( 'currentUser', $currentUser );
				}
			}
		);

		/* Load Language */
		$this->loadTranslationsFrom( __DIR__ . '/../resources/lang', 'ovic' );

		/* Load View */
		$this->loadViewsFrom( __DIR__ . '/../resources/views', 'ovic' );

		/* Publishes */
		$this->publishes_assets();
		$this->publishes_lang();
		$this->publishes_auth();
	}

	public function publishes_assets()
	{
		$this->publishes(
			[
				__DIR__ . '/../resources/views/' => resource_path( 'views' ),
			],
			'ovic-views'
		);
	}

	public function publishes_lang()
	{
		$this->publishes(
			[
				__DIR__ . '/../resources/lang' => resource_path( 'lang/ovic-core/framework' ),
			],
			'ovic-lang'
		);
	}

	public function publishes_auth()
	{
		$this->publishes(
			[
				__DIR__ . '/../resources/views/backend/auth' => resource_path( 'views/auth' ),
			],
			'ovic-auth'
		);
	}
}
