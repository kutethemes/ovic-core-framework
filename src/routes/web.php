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

Route::group(
	[ 'middleware' => [ 'web', 'auth' ] ],
	function () {
		/* Dashboard template */
		Route::get( '/dashboard', 'Ovic\Framework\DashboardController@index' )->name( 'dashboard' );

		/* User template */
		Route::resource( '/users', 'Ovic\Framework\UsersController' );

		/* Post Route */
		Route::resource( '/post', 'Ovic\Framework\PostsController' );

		/* Upload Route */
		Route::resource( '/upload', 'Ovic\Framework\UploadFileController' );

		/* Upload Filter */
		Route::match( [ 'get', 'post' ], '/filter', 'Ovic\Framework\UploadFileController@filter'
		)->name( 'file_filter' );

		/* Images */
		Route::match( [ 'get', 'post' ], 'images/{year}/{month}/{filename}', 'Ovic\Framework\ImagesController@index'
		)->name( 'get_file' );

		/* Clear Cache */
		Route::get( '/clear-cache', 'Ovic\Framework\DashboardController@clear_cache' )->name( 'clear_cache' );
	}
);