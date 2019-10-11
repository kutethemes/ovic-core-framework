<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get( 'home/',
	'ovic\framework\FrameworkController@index'
);

Route::get( 'dashboard/',
	function () {
		if ( !view()->exists( 'home' ) ) {
			return view( 'ovic::BackendLayouts.dashboard' );
		}

		return view( 'BackendLayouts.dashboard' );
	}
);