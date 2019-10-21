<?php

namespace Ovic\Framework;

use App\Http\Controllers\Controller;

class ImagesController extends Controller
{
	public function store( $year, $month, $filename )
	{
		$path = storage_path( "app/uploads/{$year}/{$month}/{$filename}" );

		if ( !file_exists( $path ) ) {
			return response( $path, 400 );
		}

		return response()->file( $path );
	}
}