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
		$view         = ovic_blade( 'home' );

		return view( $view,
			compact( 'current_time' )
		);
	}
}