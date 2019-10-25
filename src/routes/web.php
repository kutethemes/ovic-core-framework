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
		$data = [
			'id'        => '',
			'parent_id' => '',
			'router'    => [
				'title'       => '',
				'icon'        => '',
				'module'      => '',
				'controller'  => '',
				'custom_link' => '',
				'description' => '',
			],
			'ordering'  => '',
			'position'  => [
				'top'    => 'Top',
				'left'   => 'Left',
				'right'  => 'Right',
				'bottom' => 'Bottom',
			],
			'access'    => [
				'1' => 'Backend',
				'2' => 'Frontend',
				'0' => 'Public',
			],
			'status'    => [
				'1' => 'Active',
				'2' => 'Inactive',
				'0' => 'Hidden',
			],
		];
		/* Dashboard Route */
		Route::get( 'dashboard', 'Ovic\Framework\DashboardController@index' )->name( 'dashboard' );

		/* User Route */
		Route::post( 'users/list', 'Ovic\Framework\UsersController@users' )->name( 'users.list' );
		Route::resource( 'users', 'Ovic\Framework\UsersController' );

		/* Roles Route */
		Route::post( 'roles/list', 'Ovic\Framework\RolesController@roles' )->name( 'roles.list' );
		Route::resource( 'roles', 'Ovic\Framework\RolesController' );

		/* Ucases Route */
		Route::resource( 'ucases', 'Ovic\Framework\UcasesController' );

		/* Post Route */
		Route::resource( 'post', 'Ovic\Framework\PostsController' );

		/* Upload Route */
		Route::post( 'upload/remove', 'Ovic\Framework\UploadFileController@remove' )->name( 'upload.remove' );
		Route::post( 'upload/filter', 'Ovic\Framework\UploadFileController@filter' )->name( 'upload.filter' );
		Route::resource( 'upload', 'Ovic\Framework\UploadFileController' );

		/* Images */
		Route::get( 'images/{year}/{month}/{filename}', 'Ovic\Framework\ImagesController@index' )->name( 'get_file' );

		/* Clear Cache */
		Route::get( 'clear-cache', 'Ovic\Framework\DashboardController@clear_cache' )->name( 'clear_cache' );
	}
);