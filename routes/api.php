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
//    Route::middleware('loginCheck')->group(function () {
//
//        //Media
//        Route::resource('media', 'MediaController', ['only' => ['index', 'store', 'destroy']]);
//
//        //Group
//        Route::post('group/update', 'GroupController@update');
//        Route::resource('group', 'GroupController');
//
//        //Username in group Check
//        Route::post('group/username', 'GroupController@usernameCheck');
//
//        //User Setting
//        Route::resource('user/setting', 'UserSettingController', ['only' => ['index', 'store']]);
//
//        //Group Favourite
//        Route::resource('group/favourite', 'GroupChannelFavouriteController', ['only' => ['index', 'destroy', 'store']]);
//
//        //Group Member
//        Route::post('groupSubscriptions/{thread}/leave', 'GroupSubscriptionController@subscriptionLeft');
//        Route::post('groupSubscriptions/{thread}/kicked', 'GroupSubscriptionController@subscriptionKicked');
//        Route::post('groupSubscriptions/{thread}/joined', 'GroupSubscriptionController@subscriptionJoined');
//        Route::resource('groupSubscriptions/{thread}/', 'GroupSubscriptionController');
//
//        //Group Payment
//        Route::delete('groupPayment/{id}', 'GroupPaymentMethodController@destroy');
//        Route::resource('groupPayment/{thread}/', 'GroupPaymentMethodController');
//
//        //Channel Favourite
//        Route::resource('channel/favourite', 'GroupChannelFavouriteController', ['only' => ['index', 'destroy', 'store']]);
//
//        //Channel
//        Route::get('channel/index/vas', 'ChannelController@indexVas');
//        Route::post('channel/update', 'ChannelController@update');
//        Route::resource('channel', 'ChannelController');
//
//        //Username in channel Check
//        Route::post('channel/username', 'ChannelController@usernameCheck');
//
//        //Channel Members
//        Route::post('channelSubscriptions/{thread}/leave', 'ChannelSubscriptionController@subscriptionLeft');
//        Route::post('channelSubscriptions/{thread}/kicked', 'ChannelSubscriptionController@subscriptionKicked');
//        Route::post('channelSubscriptions/{thread}/joined', 'ChannelSubscriptionController@subscriptionJoined');
//        Route::resource('channelSubscriptions/{thread}/', 'ChannelSubscriptionController');
//
//        //Conversation
//        Route::resource('conversation', 'ConversationController', ['only' => ['index', 'destroy', 'update']]);
//
//        //Conversation Favourite
//        Route::resource('conversation/favourite', 'ConversationFavouriteController', ['only' => ['index', 'destroy', 'store']]);
//
//        //Conversation setting
//        Route::resource('conversation/setting/{thread}/', 'ConversationsSettingController', ['only' => ['index', 'store']]);
//
//        //Like Moment
//        Route::post('moment/reaction', 'MomentController@reaction');
//
//        //Comment Moment
//        Route::get('moment/comment', 'MomentController@indexComment');
//        Route::post('moment/comment', 'MomentController@storeComment');
//        Route::post('moment/comment/delete', 'MomentController@destroyComment');
//
//        //Moment
//        Route::resource('moment', 'MomentController', ['only' => ['index', 'store', 'destroy']]);
//        Route::get('timeline', 'MomentController@moment_list');
//
//        //Relation
//        Route::get('relation/pending', 'RelationController@relationPending');
//        Route::post('relation/accept', 'RelationController@relationAccept');
//        Route::post('relation/decline', 'RelationController@relationDecline');
//        Route::resource('relation', 'RelationController', ['only' => ['index', 'store', 'destroy']]);
//
//        //User
//        Route::post('user/update', 'UserController@update');
//        Route::post('user/fcm', 'UserController@fcm');
//        Route::post('user/apns', 'UserController@apns');
//        Route::get('user', 'UserController@index');
//        Route::get('user/allPhoto/{member_id}', 'UserController@allPhoto');
//
//        //Contact
//        Route::post('/contact/add', 'ContactController@addContact');
//        Route::resource('/contact', 'ContactController');
//
//        //UserStickerPack
//        Route::resource('/user/sticker', 'UserStickerController');
//        Route::post('/user/sticker/suggest', 'UserStickerController@suggest');
//
//        //StickerPack
//        Route::post('/sticker/pack/update/{id}', 'StickerController@update');
//        Route::resource('/sticker/pack', 'StickerController');
//        //sticker
//        Route::get('/sticker/{sticker_pack_id}', 'StickerController@sticker_index');
//        Route::post('/sticker/store', 'StickerController@sticker_store');
//        Route::get('/sticker/delete/{sticker_id}', 'StickerController@sticker_delete');
//        //sticker_emoji
//        Route::get('/sticker/emoji/{sticker_id}', 'StickerController@sticker_emoji_index');
//        Route::post('/sticker/emoji/store', 'StickerController@sticker_emoji_store');
//        Route::get('/sticker/emoji/delete/{emoji_id}', 'StickerController@sticker_emoji_delete');
//
//        //Follow
//        Route::get('moment/follower', 'MomentController@follower_list');
//        Route::get('moment/following', 'MomentController@following_list');
//        Route::post('moment/follow', 'MomentController@follower_store');
//        Route::get('moment/follow/pending', 'MomentController@follower_pending');
//        Route::get('moment/follow/block', 'MomentController@follower_block');
//        Route::post('moment/follow/delete', 'MomentController@follower_status');
//
//        //Wallet
//        Route::resource('/wallet', 'WalletController');
//        Route::post('wallet/update', 'WalletController@update');
//
//        //Users Block
//        Route::get('user/block/data', 'UsersBlockController@getData');
//        Route::get('user/block/usersId', 'UsersBlockController@getDataOnlyUsersId');
//        Route::post('user/block', 'UsersBlockController@block');
//        Route::post('user/unBlock', 'UsersBlockController@unBlock');
//
//        //Search
//        Route::get('searchAllSections', 'SearchController@searchAllSections');
//        Route::get('searchGroupChannel', 'SearchController@searchGroupChannel');
//        Route::get('searchUsers', 'SearchController@searchUsers');
//        Route::get('searchMessages', 'SearchController@searchMessages');
//        Route::get('users/suggestions', 'SearchController@usersSuggestions');
//
//        //Points
//        Route::get('user/best/point', 'UsersPointsHistoryController@indexBest');
//        Route::post('user/point/checkIn', 'UsersPointsHistoryController@pointsCheckIn');
//
//        //Task
//        Route::get('task/{category}', 'TasksController@index');
//    });

});