<?php

namespace App;

use App\Inside\Constants;
use Illuminate\Database\Eloquent\Model;

class ArticleFavourite extends Model
{
    protected $table = Constants::ARTICLE_FAVOURITE_DB;
    protected $fillable = [
        "article_id", 'user_id', 'created_at'
    ];
    public $timestamps = false;
}
