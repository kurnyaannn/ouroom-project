<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;


class UserToken extends Model
{
    protected $table = 'tbl_user_token';
    protected $guard_name = 'web';

    protected $fillable = [
        'user_id', 'token', 'date_expired'
    ];

    public static $rules = [
        'user_id' => 'required | unique',
        'token' => 'string | unique',
        'date_expired' => 'required | integer'
    ];

    protected $hidden = [];
}
