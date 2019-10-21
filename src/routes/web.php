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

		/* Media template */
		Route::get( '/media', 'Ovic\Framework\UploadFileController@index' )->name( 'media' );

		/* User template */
		Route::get( '/users', 'Ovic\Framework\UsersController@index' )->name( 'users' );

		/* Post Route */
		Route::match( [ 'get', 'post' ], '/new-post',
			'Ovic\Framework\PostsController@create'
		)->name( 'new_post' );

		Route::match( [ 'get', 'post' ], '/update-post',
			'Ovic\Framework\PostsController@update'
		)->name( 'update_post' );

		Route::match( [ 'get', 'post' ], '/remove-post',
			'Ovic\Framework\PostsController@remove'
		)->name( 'remove_post' );

		/* Upload File */
		Route::match( [ 'get', 'post' ], '/upload',
			'Ovic\Framework\UploadFileController@upload'
		)->name( 'upload_file' );

		Route::match( [ 'get', 'post' ], '/remove-file',
			'Ovic\Framework\UploadFileController@remove'
		)->name( 'remove_file' );

		Route::match( [ 'get', 'post' ], '/file-filter',
			'Ovic\Framework\UploadFileController@filter'
		)->name( 'file_filter' );

		/* Images */
		Route::match( [ 'get', 'post' ], 'images/{year}/{month}/{filename}',
			'Ovic\Framework\ImagesController@store'
		)->name( 'get_file' );

		/* Clear Cache */
		Route::get( '/clear-cache', 'Ovic\Framework\DashboardController@clear_cache' )->name( 'clear_cache' );
	}
);