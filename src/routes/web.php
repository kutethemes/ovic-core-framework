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

use Ovic\Framework\Ucases;

/* Dashboard Route */
Route::get('dashboard', 'Ovic\Framework\DashboardController@index');

/* Admin routes */
Route::group(
    [
        'prefix'     => '',
        'middleware' => [ 'web', 'auth', 'permission' ],
    ],
    function () {

        Auth::routes();

        /* Backend routes */
        Route::group([],
            function () {
                if ( Ucases::hasTable() ) {
                    Ucases::GetRoute('backend');
                }
            }
        );

        /* Frontend routes */
        Route::group([],
            function () {
                if ( Ucases::hasTable() ) {
                    Ucases::GetRoute('frontend');
                }
            }
        );

//        /* User Route */
//        Route::resource('users', 'Ovic\Framework\UsersController');
//
//        /* Roles Route */
//        Route::resource('roles', 'Ovic\Framework\RolesController');
//
//        /* Permission Route */
//        Route::resource('permission', 'Ovic\Framework\PermissionController');
//
//        /* Upload Route */
//        Route::resource('upload', 'Ovic\Framework\UploadFileController');

        /* Ucases Route */
        Route::resource('ucases', 'Ovic\Framework\UcasesController');

        /* Post Route */
        Route::resource('post', 'Ovic\Framework\PostsController');

        /* Icon fonts */
        Route::get('icon-fonts', 'Ovic\Framework\IconController@getIcon')->name('icon.fonts');

        /* Images */
        Route::get('images/{year}/{month}/{filename}', 'Ovic\Framework\ImagesController@index')->name('images.build');

        /* Clear Cache */
        Route::get('clear-cache', 'Ovic\Framework\DashboardController@clear_cache')->name('system.cache');

        /* System config */
        Route::get('config', 'Ovic\Framework\DashboardController@config')->name('config');
    }
);

/* Public routes */
Route::group([],
    function () {
        if ( Ucases::hasTable() ) {
            Ucases::GetRoute('public');
        }
    }
);
