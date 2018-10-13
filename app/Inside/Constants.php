<?php

namespace App\Inside;

class Constants
{

    Const LOGIN_TYPE_SMS = "login_with_sms";
    Const LOGIN_TYPE_CALL = "login_with_call";
    Const LOGIN_TYPE_EMAIL = "login_with_call";

    //DataBase
    const USERS_DB = 'users';
    const USERS_LOGIN_TOKEN_DB = 'users_login_token';
    const USERS_LOGIN_TOKEN_LOG_DB = 'users_login_token_log';
    const CATEGORY_DB = 'category';
    const ARTICLE_DB = 'article';
    const ARTICLE_FAVOURITE_DB = 'article_favourite';


    const PHOTO_TYPE = ["image/gif", "image/jpeg", "image/jpg", "image/png", "image/PNG", "image/GIF", 'image/*'];

//    const VIDEO_TYPE = ["video/x-flv", "video/mp4", "application/x-mpegURL", "video/MP2T", "video/3gpp", "video/quicktime",
//        "video/x-msvideo", "video/x-ms-wmv", "avi", "swf", "flv", "wmv", "application/octet-stream",
//        "video/quicktime", "video/MP2T", "video/3gpp", "video/x-msvideo", "video/x-ms-wmv", "video/x-ms-wmv",
//        "video/x-matroska", "video/mpeg", "application/x-shockwave-flash", "video/webm", "video/mov", 'video/*'];
    const VIDEO_TYPE = ["video/mp4"];

    const AUDIO_TYPE = ["audio/mpeg", "audio/x-wav", "audio/ogg", "audio/mp4", "audio/mp3", "audio/midi", "audio/basic", "audio/adpcm", "audio//s3m",
        "audio/silk", "audio/webm", "audio/m4a"];
}