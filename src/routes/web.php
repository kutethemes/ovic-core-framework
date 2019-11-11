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

        /* Backend routes */

        Route::group([],
            function () {

                if ( Ucases::hasTable() ) {

                    $ucases = Ucases::GetRoute('backend');

                    if ( !empty($ucases) ) {
                        foreach ( $ucases as $ucase ) {
                            if ( !empty($ucase['route']['custom_link']) ) {
                                Route::get($ucase['route']['custom_link']);
                            } else {
                                $module = "";
                                if ( !empty($ucase['route']['module']) ) {
                                    $module = "{$ucase['route']['module']}:";
                                }
                                Route::resource("{$ucase['slug']}", "{$module}{$ucase['route']['controller']}");
                            }
                        }
                    }

                }

            }
        );

        /* Frontend routes */

        Route::group([],
            function () {

                if ( Ucases::hasTable() ) {

                    $ucases = Ucases::GetRoute('frontend');

                    if ( !empty($ucases) ) {
                        foreach ( $ucases as $ucase ) {
                            if ( !empty($ucase['route']['custom_link']) ) {
                                Route::get($ucase['route']['custom_link']);
                            } else {
                                $module = "";
                                if ( !empty($ucase['route']['module']) ) {
                                    $module = "{$ucase['route']['module']}::";
                                }
                                Route::resource($ucase['slug'], "{$module}{$ucase['route']['controller']}");
                            }
                        }
                    }

                }

            }
        );

//        /* User Route */
//        Route::resource('users', 'Ovic\Framework\UsersController');
//
//        /* Roles Route */
//        Route::resource('roles', 'Ovic\Framework\RolesController');
//
//        /* Ucases Route */
//        Route::resource('ucases', 'Ovic\Framework\UcasesController');
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

            $ucases = Ucases::GetRoute('public');

            if ( !empty($ucases) ) {
                foreach ( $ucases as $ucase ) {
                    if ( !empty($ucase['route']['custom_link']) ) {
                        Route::get($ucase['route']['custom_link']);
                    } else {
                        $module = "";
                        if ( !empty($ucase['route']['module']) ) {
                            $module = "{$ucase['route']['module']}:";
                        }
                        Route::resource($ucase['slug'], "{$module}{$ucase['route']['controller']}");
                    }
                }
            }

        }

    }
);

/* Clear Cache */

Route::get('clear-cache', 'Ovic\Framework\DashboardController@clear_cache')->name('cache.clear');
