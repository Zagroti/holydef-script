<?php

namespace App;

use App\Inside\Constants;
use Illuminate\Database\Eloquent\Model;

class UsersToken extends Model
{
    protected $table = Constants::USERS_TOKEN_DB;
    protected $fillable = ['user_id', 'token'];
}
