<?php

namespace App\Model\ActionLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ActionLog extends Model
{
    const TYPE_GENERAL = 10;

    protected $table = 'tbl_action_log';
    protected $guard_name = 'web';
    protected $fillable = [
        'user_id',
        'action_type',
        'is_error',
        'action_message',
        'date'
    ];

    public static $rules = [
        'user_id' => 'nullable | interger',
        'action_type' => 'required | interger',
        'date' => 'required | date',
        'is_error' => 'required | interger',
    ];

    protected $hidden = [];

    public function getUser()
    {
        return $this->hasOne('App\Model\User\User', 'id', 'user_id');
    }
}
