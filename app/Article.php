<?php

namespace App;

use App\Inside\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    protected $table = Constants::ARTICLE_DB;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "cat_id", "title", "short_description", "description",
        "image", "type_image", "video", "type_video", "audio", "type_audio"
    ];

    protected $dates = ['deleted_at'];
}
