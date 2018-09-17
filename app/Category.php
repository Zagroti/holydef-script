<?php

namespace App;

use App\Inside\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    protected $table = Constants::CATEGORY_DB;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "title", "description", "path", "type_path"
    ];

    protected $dates = ['deleted_at'];

}
