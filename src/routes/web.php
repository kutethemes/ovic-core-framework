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

Route::group( [ 'middleware' => [ 'web' ] ],
	function () {
		Route::get( '/dashboard',
			function () {
				if ( \Illuminate\Support\Facades\Auth::check() ) {
					return view( ovic_blade( 'Backend.dashboard.app' ) );
				} else {
					return view( 'auth.login' );
				}
			}
		);
	}
);