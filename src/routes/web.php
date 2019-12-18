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
    [
        'prefix'     => '',
        'middleware' => [ 'web', 'auth', 'permission' ],
    ],
    function () {

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
//        /* Profile Route */
//        Route::resource('profile', 'Ovic\Framework\ProfileController');
//
//        /* Roles Route */
//        Route::resource('roles', 'Ovic\Framework\RolesController');
//
//        /* Permission Route */
//        Route::resource('permission', 'Ovic\Framework\PermissionController');
//
//        /* Upload Route */
//        Route::resource('upload', 'Ovic\Framework\UploadFileController');
//
//        /* Email Route */
//        Route::resource('email', 'Ovic\Framework\EmailController');
//
//        /* Importer Route */
//        Route::resource('importer', 'Ovic\Framework\ImporterController');

        /* Ucases Route */
        Route::resource('ucases', 'Ovic\Framework\UcasesController');

        /* Post Route */
        Route::resource('post', 'Ovic\Framework\PostsController');

        /* Icon fonts */
        Route::get('icon-fonts', 'Ovic\Framework\IconController@getIcon')->name('icon.fonts');

        /* System config */
        Route::get('configs', 'Ovic\Framework\DashboardController@configs')->name('configs');

        /* Dashboard Route */
        Route::get('dashboard', 'Ovic\Framework\DashboardController@index')->name('dashboard');

        /* Systems */
        Route::get('systems/{action}', 'Ovic\Framework\DashboardController@systems')->name('systems.action');
    }
);

/* Public routes */
Route::group([],
    function () {
        if ( \Ovic\Framework\Ucases::hasTable() ) {
            \Ovic\Framework\Ucases::GetRoute('public');
        }

        /* Images */
        Route::get('images/{year}/{month}/{filename}', 'Ovic\Framework\ImagesController@getImage')->name('images.build');
    }
);
