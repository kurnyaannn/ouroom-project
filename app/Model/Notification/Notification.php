<?php

namespace App\Model\Notification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Notification extends Model
{
    const NOTIFICATION_TYPE_TEACHER = 20;
    const NOTIFICATION_TYPE_SISWA = 30;

    protected $table = 'tbl_notification';
    protected $guard_name = 'web';

    protected $fillable = [
        'notification_type',
        'notification_title',
        'notification_message',
        'date'
    ];

    public static $rules = [
        'notification_type' => 'required | interger',
        'notification_title' => 'required | string',
        'date' => 'required | date',
    ];

    protected $hidden = [];

    public static function getTypeMeaning($notification_type)
    {
        switch ($notification_type) {
            case static::NOTIFICATION_TYPE_TEACHER:
                return 'Untuk Guru';
            case static::NOTIFICATION_TYPE_SISWA:
                return 'Untuk Siswa';
            default:
                return '';
        }
    }
}
