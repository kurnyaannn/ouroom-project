<?php

namespace App\Model\RoleHasPermission;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleHasPermission extends Model
{
    protected $table = 'role_has_permissions';
    protected $guard_name = 'web';

    protected $fillable = [
        'permission_id',
        'role_id'
    ];

    public static $rules = [
        'permission_id' => 'required | interger',
        'role_id' => 'required | interger'
    ];

    protected $hidden = [];

    public static function getHasPermission($role_id)
    {
        return self::all()->where('role_id', $role_id);
    }
}
