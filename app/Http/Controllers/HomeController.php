<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\StudentClass\StudentClass;
use App\Model\User\User;
use App\Model\User\UserLoginHistory;
use Carbon\Carbon;
use Auth;
use DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $id_user = Auth::id();
        $last_login = UserLoginHistory::findLastlogin();
        if ($last_login != null) {
            $last_login = Carbon::parse($last_login->date);
            $last_login = $last_login->format('d M Y');
        }
        if ($this->getUserPermission('index home')) {
            if ($this->getUserLogin()->account_type == User::ACCOUNT_TYPE_TEACHER) {
                $siswa = StudentClass::where('teacher_id', $this->getUserLogin()->id)
                    ->with('hasUser')
                    ->count();
                $class = StudentClass::where('teacher_id', $this->getUserLogin()->id)->count();
                $teacher = User::where('account_type', User::ACCOUNT_TYPE_TEACHER)->count();
                return view('home.index', ['last_login' => $last_login, 'active' => 'home', 'id_user' => $id_user, 'siswa' => $siswa, 'class' => $class, 'teacher' => $teacher]);
            } else if ($this->getUserLogin()->account_type == User::ACCOUNT_TYPE_SISWA) {
                $jumlah_kelas = DB::table('tbl_class_user')
                    ->where('user_id', $id_user)
                    ->count();
                $id_kelas = DB::table('tbl_class_user')
                    ->where('user_id', $id_user)
                    ->pluck('class_id')
                    ->toArray();
                $feed = [];
                $i = 0;
                for($i; $i<$jumlah_kelas; $i++){
                    $feed[$i] = DB::table('tbl_feed')->where('class_id', $id_kelas[$i])
                        ->get();
                }
                return view('home.index', ['last_login' => $last_login, 'active' => 'home', 'id_user' => $id_user, 'feed' => $feed]);
            } else {
                $siswa = User::where('account_type', User::ACCOUNT_TYPE_SISWA)->count();
                $class = StudentClass::count();
                $teacher = User::where('account_type', User::ACCOUNT_TYPE_TEACHER)->count();
                return view('home.index', ['last_login' => $last_login, 'active' => 'home', 'id_user' => $id_user, 'siswa' => $siswa, 'class' => $class, 'teacher' => $teacher]);
            }
            $this->systemLog(false, 'Mengakses Halaman Home');
        } else {
            $this->systemLog(true, 'Gagal Mengakses Halaman Home');
            return view('error.unauthorized', ['active' => 'home']);
        }
    }
}
