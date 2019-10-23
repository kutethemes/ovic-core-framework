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
		/* Dashboard Route */
		Route::get( 'dashboard', 'Ovic\Framework\DashboardController@index' )->name( 'dashboard' );

		/* User Route */
		Route::post( 'users/list', 'Ovic\Framework\UsersController@users' )->name( 'users.list' );
		Route::resource( 'users', 'Ovic\Framework\UsersController' );

		/* Roles Route */
		Route::post( 'roles/list', 'Ovic\Framework\RolesController@roles' )->name( 'roles.list' );
		Route::resource( 'roles', 'Ovic\Framework\RolesController' );

		/* Post Route */
		Route::resource( 'post', 'Ovic\Framework\PostsController' );

		/* Upload Route */
		Route::get( 'upload/filter', 'Ovic\Framework\UploadFileController@filter' )->name( 'upload.filter' );
		Route::resource( 'upload', 'Ovic\Framework\UploadFileController' );

		/* Images */
		Route::get( 'images/{year}/{month}/{filename}', 'Ovic\Framework\ImagesController@index' )->name( 'get_file' );

		/* Clear Cache */
		Route::get( 'clear-cache', 'Ovic\Framework\DashboardController@clear_cache' )->name( 'clear_cache' );
	}
);