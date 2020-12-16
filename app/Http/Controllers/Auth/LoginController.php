<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Model\User\User;
use App\Model\User\UserLoginHistory;
use Auth;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        $years = array_combine(range(date("Y"), 2018), range(date("Y"), 2018));
        return view('auth.register', ['years' => $years]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|max:255',
            'password' => 'required',
        ]);
        $crendentials = ['username' => $request->username, 'password' => $request->password];
        if (Auth::attempt($crendentials, $request->remember)) {
            $user_login_history = new UserLoginHistory();
            $user_login_history->user_id = Auth::user()->id;
            $user_login_history->last_login_ip =  $request->ip();
            $user_login_history->date = Carbon::now();

            if ($user_login_history->save()) {
                return redirect('/');
            } else {
                return Redirect::back()->withErrors(['Terjadi kegagalan sistem', 'error_login']);
            }
        } else {
            return Redirect::back()->withErrors(['username dan password tidak sesuai', 'error_login']);
        }
    }

    public function register(StoreUserRequest $request)
    {
        $this->validate($request, [
            'full_name' => 'required|max:255',
            'email' => 'required|max:255|unique:tbl_user',
            'angkatan' => 'required',
            'kelas' => 'required',
            'username' => 'required|max:255|unique:tbl_user',
            'password' => 'required|min:6|confirmed',
        ]);
        $user = new User();
        $user->full_name = $request->get('full_name');
        $user->email = $request->get('email');
        $user->jenis_kelamin = $request->get('jenis_kelamin');
        $user->angkatan = $request->get('angkatan');
        $user->kelas = $request->get('kelas');
        $user->username = $request->get('username');
        $user->password = $request->get('password');
        $user->account_type = 'Siswa';
        $user->assignRole('Siswa');
        $user->save();
        if (!$user->save()) {
            return redirect()->back()->with('alert_error', 'Gagal Disimpan');
        } else {
            return redirect('/auth/login')->withSuccess('Registrasi berhasil, silahkan Login.');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/auth/login');
    }

    function authenticated(Request $request, $user)
    {
        $user->last_login = Carbon::now()->toDateTimeString();
        $user->last_login_ip = $request->getClientIp();
        $user->save();
    }
}
