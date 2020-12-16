<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use App\Model\Notification\Notification;
use App\Model\UserNotification\UserNotification;
use App\Model\ActionLog\ActionLog;
use App\Model\User\User;
use App\Http\Resources\Notification\NotificationResource;
use App\Http\Requests\Notification\StoreNotificationRequest;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Notification::orderBy('created_at', 'DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('notification_type', function (Notification $value) {
                    return Notification::getTypeMeaning($value->notification_type);
                })
                ->addColumn('date', function (Notification $value) {
                    $date =  Carbon::parse($value->date);
                    return $date->format('d M Y');
                })
                ->make(true);
        }
        if ($this->getUserPermission('index notification')) {
            $this->systemLog(false, 'Mengakses Halaman Notifikasi');
            return view('notification.index', ['active' => 'notification']);
        } else {
            $this->systemLog(true, 'Gagal Mengakses Halaman Notification');
            return view('error.unauthorized', ['active' => 'notification']);
        }
    }

    public function getDetail(Request $request)
    {
        if ($request->ajax()) {
            $user_notification = UserNotification::where('user_id', Auth::user()->id)->where('notification_id', $request->get('idnotification'))->where('status', UserNotification::STATUS_UNREAD)->first();
            if ($user_notification != null) {
                $user_notification->status = UserNotification::4_READ;
                $user_notification->save();
            }
            $notification = Notification::findOrFail($request->get('idnotification'));
            return new NotificationResource($notification);
        }
    }

    public function store(StoreNotificationRequest $request)
    {
        DB::beginTransaction();
        $notification = new Notification();
        $notification->notification_type    = $request->get('notification_type');
        $notification->notification_title   = $request->get('notification_title');
        $notification->notification_message = $request->get('notification_message');
        $notification->date = Carbon::now();
        if (!$notification->save()) {
            DB::rollBack();
            $this->systemLog(true, 'Gagal Menyimpan Notification');
            return redirect('notification')->with('alert_error', 'Gagal Disimpan');
        }
        if ($request->notification_type == '20') {
            $all_user_teacher = User::where('account_type', '=', 'Guru')->get();
            foreach ($all_user_teacher as $user_teacher) {
                $user_notification = new UserNotification();
                $user_notification->notification_id = $notification->id;
                $user_notification->user_id = $user_teacher->id;
                $user_notification->status = UserNotification::STATUS_UNREAD;
                if (!$user_notification->save()) {
                    DB::rollBack();
                }
            }
        } else if ($request->notification_type == '30'){
            $all_user_siswa = User::where('account_type', '=', 'Siswa')->get();
            foreach ($all_user_siswa as $user_siswa) {
                $user_notification = new UserNotification();
                $user_notification->notification_id = $notification->id;
                $user_notification->user_id = $user_siswa->id;
                $user_notification->status = UserNotification::STATUS_UNREAD;
                if (!$user_notification->save()) {
                    DB::rollBack();
                }
            }
        }

        if ($this->getUserPermission('create notification')) {
            DB::commit();
            $this->systemLog(false, 'Berhasil Menyimpan Input Notification');
            return redirect('notification')->with('alert_success', 'Berhasil Disimpan');
        } else {
            DB::rollBack();
            $this->systemLog(true, 'Gagal Menyimpan Input Notification');
            return redirect('notification')->with('alert_error', 'Gagal Disimpan');
        }
    }
}
