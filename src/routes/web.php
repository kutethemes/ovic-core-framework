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
		/* Dashboard template */
		Route::get( '/dashboard',
			function () {
				return view( ovic_blade( 'Backend.dashboard.app' ) );
			}
		)->name( 'dashboard' );

		/* Media template */
		Route::get( '/media',
			function () {
				return view( ovic_blade( 'Backend.media.app' ) );
			}
		)->name( 'media' );

		/* User template */
		Route::get( '/users',
			function () {
				return view( ovic_blade( 'Backend.users.app' ) );
			}
		)->name( 'users' );

		/* Post Route */
		Route::get( '/new-post', 'Ovic\Framework\PostsController@create' )->name( 'new_post' );
		Route::post( '/new-post', 'Ovic\Framework\PostsController@create' )->name( 'new_post' );

		Route::get( '/update-post', 'Ovic\Framework\PostsController@update' )->name( 'update_post' );
		Route::post( '/update-post', 'Ovic\Framework\PostsController@update' )->name( 'update_post' );

		Route::get( '/remove-post', 'Ovic\Framework\PostsController@remove' )->name( 'remove_post' );
		Route::post( '/remove-post', 'Ovic\Framework\PostsController@remove' )->name( 'remove_post' );

		/* Post File */
		Route::get( '/upload', 'Ovic\Framework\UploadFileController@upload' )->name( 'upload_file' );
		Route::post( '/upload', 'Ovic\Framework\UploadFileController@upload' )->name( 'upload_file' );

		Route::get( '/remove-file', 'Ovic\Framework\UploadFileController@remove' )->name( 'remove_file' );
		Route::post( '/remove-file', 'Ovic\Framework\UploadFileController@remove' )->name( 'remove_file' );

		Route::get( '/file-filter', 'Ovic\Framework\UploadFileController@filter' )->name( 'file_filter' );
		Route::post( '/file-filter', 'Ovic\Framework\UploadFileController@filter' )->name( 'file_filter' );

		Route::get( 'images/{year}/{month}/{filename}', 'Ovic\Framework\ImageController@store' )->name( 'get_file' );
		Route::post( 'images/{year}/{month}/{filename}', 'Ovic\Framework\ImageController@store' )->name( 'get_file' );
	}
);