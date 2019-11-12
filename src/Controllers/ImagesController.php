<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;

class ImagesController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	public function index( $year, $month, $filename )
	{
		$path = storage_path( "app/uploads/{$year}/{$month}/{$filename}" );

		if ( !file_exists( $path ) ) {
			return response( $path, 400 );
		}

		return response()->file( $path );
	}
}
