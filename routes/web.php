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
        return redirect('/lines');
    });


    Route::group(['middleware' => 'wechat.share'], function () {

        Route::get('lines', 'RootController@view_lines');

        Route::get('line/{id?}', 'RootController@view_line');
//        Route::get('line', 'RootController@view_line');

        Route::get('point/{id?}', 'RootController@view_point');
//        Route::get('point', 'RootController@view_point');

        Route::get('u/{id?}', 'RootController@view_user');

    });


    Route::group(['middleware' => 'login'], function () {

        Route::post('item/collect/save', 'RootController@item_collect_save');
        Route::post('item/collect/cancel', 'RootController@item_collect_cancel');

        Route::post('item/favor/save', 'RootController@item_favor_save');
        Route::post('item/favor/cancel', 'RootController@item_favor_cancel');

        Route::post('item/comment/save', 'RootController@item_comment_save');
        Route::post('item/reply/save', 'RootController@item_reply_save');

        Route::post('item/comment/favor/save', 'RootController@item_comment_favor_save');
        Route::post('item/comment/favor/cancel', 'RootController@item_comment_favor_cancel');

    });

    Route::post('item/comment/get', 'RootController@item_comment_get');
    Route::post('item/comment/get_html', 'RootController@item_comment_get_html');
    Route::post('item/reply/get', 'RootController@item_reply_get');

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
    Route::group(['middleware' => ['home','notification']], function () {

        $controller = 'HomeController';

        Route::get('/', $controller.'@index');


        // 【info】
        Route::group(['prefix' => 'info'], function () {

            $controller = 'HomeController';

            Route::get('index', $controller.'@info_index');
            Route::match(['get','post'], 'edit', $controller.'@infoEditAction');

            Route::match(['get','post'], 'password/reset', $controller.'@passwordResetAction');

        });

        // 【线】
        Route::group(['prefix' => 'line'], function () {

            $controller = 'LineController';

            Route::get('/', $controller.'@index');
            Route::get('create', $controller.'@createAction');
            Route::match(['get','post'], 'edit', $controller.'@editAction');
            Route::match(['get','post'], 'list', $controller.'@viewList');
            Route::post('delete', $controller.'@deleteAction');
            Route::post('enable', $controller.'@enableAction');
            Route::post('disable', $controller.'@disableAction');

            Route::match(['get','post'], 'point', $controller.'@viewPointList');

        });

        // 【点】
        Route::group(['prefix' => 'point'], function () {

            $controller = 'PointController';

            Route::get('/', $controller.'@index');
            Route::get('create', $controller.'@createAction');
            Route::match(['get','post'], 'edit', $controller.'@editAction');
            Route::match(['get','post'], 'list', $controller.'@viewList');
            Route::post('delete', $controller.'@deleteAction');
            Route::post('enable', $controller.'@enableAction');
            Route::post('disable', $controller.'@disableAction');

        });



        // 收藏
        Route::group(['prefix' => 'collect'], function () {

            $controller = 'OtherController';

            Route::match(['get','post'], 'line/list', $controller.'@collect_line_viewList');
            Route::match(['get','post'], 'point/list', $controller.'@collect_point_viewList');
            Route::post('line/delete', $controller.'@collect_line_deleteAction');
            Route::post('point/delete', $controller.'@collect_point_deleteAction');
        });

        // 点赞
        Route::group(['prefix' => 'favor'], function () {

            $controller = 'OtherController';

            Route::match(['get','post'], 'line/list', $controller.'@favor_line_viewList');
            Route::match(['get','post'], 'point/list', $controller.'@favor_point_viewList');
            Route::post('line/delete', $controller.'@favor_line_deleteAction');
            Route::post('point/delete', $controller.'@favor_point_deleteAction');
        });

        // 消息
        Route::group(['prefix' => 'notification'], function () {

            $controller = 'NotificationController';

            Route::get('comment', $controller.'@comment');
            Route::get('favor', $controller.'@favor');
        });


    });

});

