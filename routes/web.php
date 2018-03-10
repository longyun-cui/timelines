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

Route::get('/', function () {
    return view('welcome');
});


/*
 * Common 通用功能
 */
Route::group(['prefix' => 'common'], function () {

    $controller = "CommonController";

    // 验证码
    Route::match(['get','post'], 'change_captcha', $controller.'@change_captcha');

    //
    Route::get('dataTableI18n', function () {
        return trans('pagination.i18n');
    });
});


/*
 * Root Frontend
 */
Route::group(['namespace' => 'Front'], function () {

    Route::get('/', function () {
        return redirect('/courses');
    });

    Route::get('courses', 'RootController@view_courses');
//    Route::get('course', 'RootController@view_course');
    Route::get('course/{id?}', 'RootController@view_course');


    Route::get('u/{id?}', 'RootController@view_user');


    Route::group(['middleware' => 'login'], function () {

        Route::post('item/collect/save', 'RootController@item_collect_save');
        Route::post('item/collect/cancel', 'RootController@item_collect_cancel');

        Route::post('item/favor/save', 'RootController@item_favor_save');
        Route::post('item/favor/cancel', 'RootController@item_favor_cancel');

    });

});


/*
 * auth
 */
Route::match(['get','post'], 'login', 'Home\AuthController@user_login');
Route::match(['get','post'], 'logout', 'Home\AuthController@user_logout');
Route::match(['get','post'], 'register', 'Home\AuthController@user_register');
Route::match(['get','post'], 'activation', 'Home\AuthController@activation');



/*
 * Home Backend
 */
Route::group(['prefix' => 'home', 'namespace' => 'Home'], function () {

    /*
     * 需要登录
     */
    Route::group(['middleware' => 'home'], function () {

        $controller = 'HomeController';

        Route::get('/', $controller.'@index');


        // 作者
        Route::group(['prefix' => 'course'], function () {

            $controller = 'CourseController';

            Route::get('/', $controller.'@index');
            Route::get('create', $controller.'@createAction');
            Route::match(['get','post'], 'edit', $controller.'@editAction');
            Route::match(['get','post'], 'list', $controller.'@viewList');
            Route::post('delete', $controller.'@deleteAction');
            Route::post('enable', $controller.'@enableAction');
            Route::post('disable', $controller.'@disableAction');

            // 作者
            Route::group(['prefix' => 'content'], function () {

                $controller = 'CourseController';

                Route::match(['get','post'], '/', $controller.'@course_content_view_index');
                Route::match(['get','post'], 'edit', $controller.'@course_content_editAction');
                Route::post('get', $controller.'@course_content_getAction');
                Route::post('delete', $controller.'@course_content_deleteAction');
            });

            Route::get('select2_menus', $controller.'@select2_menus');
        });
    });

});

