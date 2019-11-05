<?php
namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware( 'auth' );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		return view( ovic_blade( 'Backend.dashboard.app' ) );
	}

	public function clear_cache()
	{
		Artisan::call( 'cache:clear' );
		Artisan::call( 'route:cache' );
		Artisan::call( 'route:clear' );
		Artisan::call( 'config:cache' );

		return '<h1>Clear All cleared</h1>';
	}
}
