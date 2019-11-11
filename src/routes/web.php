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

/* Admin routes */

Route::group(
    [
        'prefix'     => '',
        'middleware' => [ 'web', 'auth', 'permission' ],
    ],
    function () {

        Auth::routes();

        /* Dashboard Route */
        Route::resource('dashboard', 'Ovic\Framework\DashboardController');

        /* Ucases Route */
        Route::resource('ucases', 'Ovic\Framework\UcasesController');

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

        /* Post Route */
        Route::resource('post', 'Ovic\Framework\PostsController');

        /* Images */
        Route::get('images/{year}/{month}/{filename}', 'Ovic\Framework\ImagesController@index')->name('images.build');

        /* Icon fonts */
        Route::get('icon-fonts', 'Ovic\Framework\IconController@getIcon')->name('icon.fonts');
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

/* Clear Cache */

Route::get('clear-cache', 'Ovic\Framework\DashboardController@clear_cache')->name('cache.clear');
