<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1',[
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array','bindings']
], function($api) {
    //游客

    $api->post('captchas', 'CaptchasController@store')->name('api.captchas.store');

    $api->post('authorizations', 'AuthorizationsController@store')->name('api.authorizations.store');
    // 刷新token
    $api->put('authorizations/current', 'AuthorizationsController@update')->name('api.authorizations.update');
    // 删除token
    $api->delete('authorizations/current', 'AuthorizationsController@destroy')->name('api.authorizations.destroy');

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ],function($api){


        $api->post('verificationCodes','VerificationCodesController@store')->name('api.verificationCodes.store');
        $api->post('users','UsersController@store')->name('api.users.store');
        $api->get('user','UsersController@me')->name('api.user.show');

        $api->get('topics/{topic}', 'TopicsController@show')->name('api.topics.show');
        $api->get('categories', 'CategoriesController@index')->name('api.categories.index');
        $api->get('topics','TopicsController@index')->name('api.topics.index');
        $api->get('users/{user}/topics', 'TopicsController@userIndex')->name('api.users.topics.index');
        $api->get('topics/{topic}/replies', 'RepliesController@index')->name('api.topics.replies.index');
        $api->get('users/{user}/replies', 'RepliesController@userIndex')->name('api.users.replies.index');

        $api->group(['middleware' => 'api.auth'], function($api) {
            // 当前登录用户信息
            $api->get('user', 'UsersController@me')->name('api.user.show');
            $api->post('images', 'imagesController@store')->name('api.images.store');
            $api->patch('user', 'UsersController@update')->name('api.user.update');

            $api->post('topics','TopicsController@store')->name('api.topics.store');
            $api->patch('topics/{topic}', 'TopicsController@update')->name('api.topics.update');
            $api->delete('topics/{topic}', 'TopicsController@destroy')->name('api.topics.destroy');

            $api->post('topics/{topic}/replies','RepliesController@store')->name('api.replies.store');
            $api->delete('topics/{topic}/replies/{reply}','RepliesController@destroy')->name('api.replies.destroy');

            $api->get('user/notifications', 'NotificationsController@index')->name('api.user.notifications.index');
            $api->get('user/notifications/stats', 'NotificationsController@stats')->name('api.user.notifications.stats');
        });
    });


});

