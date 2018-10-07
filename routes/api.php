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


Route::namespace('Api\V1')->prefix('/v1')->group(function () {

    //Auth otp
    Route::post('auth/otp/sms', 'Auth\ZamanakController@postSmsRequest');
    Route::post('auth/otp/call', 'Auth\ZamanakController@postCallRequest');
    Route::post('auth/otp/verify', 'Auth\ZamanakController@postVerifyRequest');


    //After Login
    Route::middleware('loginCheck')->group(function () {

        //Article Favourite
        Route::resource('article/favourite', 'ArticleFavouriteController', ['only' => ['index', 'destroy', 'store']]);

        //Article
        Route::get('search', 'ArticleController@search');
        Route::get('article/{cat_id}', 'ArticleController@index');
        Route::get('article/{cat_id}/{id}', 'ArticleController@show');
        Route::get('article/{cat_id}/{id}/favourite', 'ArticleController@getIsFavourite');

        //User
//    Route::post('user/update', 'UserController@update');
//    Route::post('user/fcm', 'UserController@fcm');
//    Route::post('user/apns', 'UserController@apns');
//    Route::get('user', 'UserController@index');

    });


    Route::post('article/{cat_id}', 'ArticleController@store');
    Route::post('article/{cat_id}/update/{id}', 'ArticleController@update');
    Route::delete('article/{cat_id}/delete/{id}', 'ArticleController@destroy');

});