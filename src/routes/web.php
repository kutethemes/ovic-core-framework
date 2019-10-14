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

Route::group( [ 'middleware' => [ 'web', 'auth' ] ],
	function () {
		Route::get( '/dashboard',
			function () {
				return view( ovic_blade( 'Backend.dashboard.app' ) );
			}
		);
		Route::get( '/media',
			function () {
				return view( ovic_blade( 'Backend.media.app' ) );
			}
		);
		Route::get( '/users',
			function () {
				return view( ovic_blade( 'Backend.users.app' ) );
			}
		);
	}
);