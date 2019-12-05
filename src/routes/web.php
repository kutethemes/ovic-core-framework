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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
                if ( \Ovic\Framework\Ucases::hasTable() ) {
                    \Ovic\Framework\Ucases::GetRoute('backend');
                }
            }
        );

        /* Frontend routes */
        Route::group([],
            function () {
                if ( \Ovic\Framework\Ucases::hasTable() ) {
                    \Ovic\Framework\Ucases::GetRoute('frontend');
                }
            }
        );

//        /* User Route */
//        Route::resource('users', 'Ovic\Framework\UsersController');
//        Route::resource('users-classic', 'Ovic\Framework\UsersClassicController');
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

        /* Cache */
        Route::get('clear-cache', 'Ovic\Framework\DashboardController@clear_cache')->name('clear.cache');
        Route::get('create-cache', 'Ovic\Framework\DashboardController@create_cache')->name('create.cache');
        Route::get('update-assets', 'Ovic\Framework\DashboardController@update_assets')->name('update.assets');
        Route::get('update-modules', 'Ovic\Framework\DashboardController@update_modules')->name('update.modules');
        Route::get('dump-autoload', 'Ovic\Framework\DashboardController@dump_autoload')->name('dump.autoload');

        /* System config */
        Route::get('configs', 'Ovic\Framework\DashboardController@configs')->name('configs');

        /* Dashboard Route */
        Route::get('dashboard', 'Ovic\Framework\DashboardController@index')->name('dashboard');
    }
);

/* Public routes */
Route::group([],
    function () {
        if ( \Ovic\Framework\Ucases::hasTable() ) {
            \Ovic\Framework\Ucases::GetRoute('public');
        }

        /* Images */
        Route::get('images/{year}/{month}/{filename}', 'Ovic\Framework\ImagesController@index')->name('images.build');
    }
);
