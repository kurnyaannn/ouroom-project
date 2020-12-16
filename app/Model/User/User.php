<?php

namespace App\Model\User;

use App\Model\StudentClass\StudentClass;
use App\Model\StudentClass\Tugas;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    protected $table = 'tbl_user';
    protected $guard_name = 'web';

    const ACCOUNT_TYPE_CREATOR = "Creator";
    const ACCOUNT_TYPE_ADMIN = "Administrator";
    const ACCOUNT_TYPE_TEACHER = "Guru";
    const ACCOUNT_TYPE_SISWA = "Siswa";

    protected $fillable = [
        'username', 'jenis_kelamin', 'email', 'full_name', 'kelas', 'angkatan', 'account_type', 'password', 'status', 'profile_picture', 'last_login_at',
        'last_login_ip'
    ];

    public static $rules = [
        'username' => 'required | unique',
        'email' => 'required | unique',
        'profile_picture' => 'string',
        'full_name' => 'required | string',
        'account_type' => 'required | string',
    ];

    public static function getUser()
    {
        return self::whereNotIn('account_type', [User::ACCOUNT_TYPE_CREATOR, User::ACCOUNT_TYPE_SISWA])->get();
    }

    public static function getTeacher($search = null)
    {
        return self::where('account_type', User::ACCOUNT_TYPE_TEACHER)->where('full_name', 'like', '%' . $search . '%')->get();
    }

    public static function getSiswa($search = null)
    {
        return self::where('account_type', User::ACCOUNT_TYPE_SISWA)->where('full_name', 'like', '%' . $search . '%')->get();
    }

    public static function passwordChangeValidation($old_password, $curent_password)
    {
        if (Hash::check($old_password, $curent_password)) {
            return true;
        }
        return false;
    }

    protected $hidden = [
        'password'
    ];

    public static function userByUsername($username)
    {
        $data = static::where('username', $username)->first();
        return $data;
    }

    public static function getAccountMeaning($acount)
    {
        switch ($acount) {
            case static::ACCOUNT_TYPE_CREATOR:
                return 'Creator';
            case static::ACCOUNT_TYPE_TEACHER:
                return 'Guru';
            case static::ACCOUNT_TYPE_ADMIN:
                return 'Administrator';
            case static::ACCOUNT_TYPE_SISWA:
                return 'Siswa';
            default:
                return '';
        }
    }

    public static function checkIfTeacher($id)
    {
        $data = static::where(['account_type' => static::ACCOUNT_TYPE_TEACHER, 'id' => $id])->first();
        if ($data != null) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkIfSiswa($id)
    {
        $data = static::where(['account_type' => static::ACCOUNT_TYPE_SISWA, 'id' => $id])->first();
        if ($data != null) {
            return true;
        } else {
            return false;
        }
    }

    public function hasClass()
    {
        return $this->belongsToMany(StudentClass::class, 'tbl_class_user', 'user_id', 'class_id');
    }

    public function hasTugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
