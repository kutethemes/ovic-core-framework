<?php
namespace Ovic\Framework;

use App\Http\Controllers\Controller;

class FrameworkController extends Controller
{
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		if ( !view()->exists( 'home' ) ) {
			return view( 'ovic::home' );
		}

		return view( 'home' );
	}
}