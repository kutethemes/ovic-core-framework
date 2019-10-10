<?php
namespace Ovic\Framework;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class FrameworkController extends Controller
{
	/**
	 * @param $timezone
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index( $timezone = NULL )
	{
		$current_time = ( $timezone ) ? Carbon::now( str_replace( '-', '/', $timezone ) ) : Carbon::now();

		if ( view()->exists( 'home' ) ) {
			return view( 'home',
				compact( 'current_time' )
			);
		}

		return view( 'framework::home',
			compact( 'current_time' )
		);
	}
}