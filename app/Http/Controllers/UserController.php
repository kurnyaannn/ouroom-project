<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\PasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use Yajra\Datatables\Datatables;
use App\Model\User\User;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;
use Illuminate\Support\Facades\Hash;
use App\Model\RoleHasPermission\RoleHasPermission;
use DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::getUser();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button onclick="btnUbah(' . $row->id . ')" name="btnUbah" type="button" class="ui big inverted primary button"><span class="glyphicon glyphicon-edit"></span></button>';
                    $pass = '<button onclick="btnPass(' . $row->id . ')" name="btnPass" type="button" class="ui big inverted primary button"><span class="glyphicon glyphicon-lock"></span></button>';
                    $delete = '<button onclick="btnDel(' . $row->id . ')" name="btnDel" type="button" class="ui big red button"><span class="glyphicon glyphicon-trash"></span></button>';
                    return $btn . '&nbsp' . $pass . '&nbsp' . $delete;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        if ($this->getUserPermission('index user')) {
            $this->systemLog(false, 'Mengakses halaman manajemen user');
            return view('user.index', ['active' => 'user']);
        } else {
            $this->systemLog(true, 'Gagal Mengakses halaman manajemen user');
            return view('error.unauthorized', ['active' => 'user']);
        }
    }

    public function indexSiswa(Request $request)
    {
        if ($request->ajax()) {
            $data = User::getSiswa();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button onclick="btnUbah(' . $row->id . ')" name="btnUbah" type="button" class="ui big inverted primary button"><span class="glyphicon glyphicon-edit"></span></button>';
                    $pass = '<button onclick="btnPass(' . $row->id . ')" name="btnPass" type="button" class="ui big inverted primary button"><span class="glyphicon glyphicon-lock"></span></button>';
                    $delete = '<button onclick="btnDel(' . $row->id . ')" name="btnDel" type="button" class="ui big red button"><span class="glyphicon glyphicon-trash"></span></button>';
                    return $btn . '&nbsp' . $pass . '&nbsp' . $delete;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $years = array_combine(range(date("Y"), 2018), range(date("Y"), 2018));
        if ($this->getUserPermission('index siswa')) {
            $this->systemLog(false, 'Mengakses halaman manajemen user');
            return view('siswa.index', ['active' => 'siswa', 'years' => $years]);
        } else {
            $this->systemLog(true, 'Gagal Mengakses halaman manajemen user');
            return view('error.unauthorized', ['active' => 'siswa']);
        }
    }

    public function create()
    {
        if ($this->getUserPermission('create user')) {
            $this->systemLog(false, 'Mengakses halaman create manajemen user');
            return view('user.store', ['active' => 'user']);
        } else {
            $this->systemLog(true, 'Gagal Mengakses halaman create manajemen user');
            return view('error.unauthorized', ['active' => 'user']);
        }
    }

    public function createSiswa()
    {
        if ($this->getUserPermission('create user')) {
            $this->systemLog(false, 'Mengakses halaman create manajemen user');
            $years = array_combine(range(date("Y"), 2015), range(date("Y"), 2015));
            return view('siswa.store', ['active' => 'siswa', 'years' => $years]);
        } else {
            $this->systemLog(true, 'Gagal Mengakses halaman create manajemen user');
            return view('error.unauthorized', ['active' => 'siswa']);
        }
    }

    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();
        $user = new User();
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->full_name = $request->get('full_name');
        $user->password = $request->get('password');
        $user->account_type = $request->get('account_type');
        $user->assignRole(User::getAccountMeaning($user->account_type));
        if (!$user->save()) {
            $this->systemLog(true, 'Gagal menyimpan user');
            DB::rollBack();
            return redirect('user')->with('alert_error', 'Gagal Disimpan');
        }
        if ($this->getUserPermission('create user')) {
            $this->systemLog(false, 'Berhasil menyimpan user');
            DB::commit();
            return redirect('user')->with('alert_success', 'Berhasil Disimpan');
        } else {
            $this->systemLog(true, 'Gagal menyimpan user');
            DB::rollBack();
            return $this->getResponse(false, 505, '', 'Tidak mempunyai izin untuk aktifitas ini');
        }
    }

    public function storeSiswa(StoreUserRequest $request)
    {
        DB::beginTransaction();
        $user = new User();
        $user->full_name = $request->get('full_name');
        $user->email = $request->get('email');
        $user->jenis_kelamin = $request->get('jenis_kelamin');
        $user->kelas = $request->get('kelas');
        $user->angkatan = $request->get('angkatan');
        $user->username = $request->get('username');
        $user->password = $request->get('password');
        $user->account_type = 'Siswa';
        $user->assignRole('Siswa');
        if (!$user->save()) {
            $this->systemLog(true, 'Gagal menyimpan user');
            DB::rollBack();
            return redirect('siswa')->with('alert_error', 'Gagal Disimpan');
        }
        if ($this->getUserPermission('create siswa')) {
            $this->systemLog(false, 'Berhasil menyimpan siswa');
            DB::commit();
            return redirect('siswa')->with('alert_success', 'Berhasil Disimpan');
        } else {
            $this->systemLog(true, 'Gagal menyimpan user');
            DB::rollBack();
            return $this->getResponse(false, 505, '', 'Tidak mempunyai izin untuk aktifitas ini');
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            User::findOrFail($request->iduser)->delete();
            if ($this->getUserPermission('create user')) {
                $this->systemLog(false, 'Berhasil menghapus user');
                return $this->getResponse(true, 200, '', 'User berhasil dinonaktifkan');
            } else {
                $this->systemLog(true, 'Gagal menghapus user');
                return $this->getResponse(false, 505, '', 'Tidak mempunyai izin untuk aktifitas ini');
            }
        }
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            if ($request->iduser != null) {
                $user_id = $request->iduser;
                $userModel = User::findOrFail($user_id);
                if ($this->getUserPermission('view user')) {
                    return new UserResource($userModel);
                } else {
                    return $this->getResponse(false, 505, '', 'Tidak mempunyai izin untuk aktifitas ini');
                }
            } else {
                return $this->getResponse(false, 500, '', 'Akses gagal dilakukan');
            }
        }
    }

    public function update(UpdateUserRequest $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            $user = User::findOrFail($request->iduser);
            $user->username = $request->get('username');
            $user->email = $request->get('email');
            $user->full_name = $request->get('full_name');
            $user->angkatan = $request->get('angkatan');
            $user->kelas = $request->get('kelas');
            $old_account_type = $user->account_type;
            $user->account_type = $request->get('account_type');
            if (!$user->save()) {
                DB::rollBack();
                return $this->getResponse(true, 400, null, 'User gagal diupdate');
            }
            if ($user->removeRole(User::getAccountMeaning($old_account_type))) {
                if ($this->getUserPermission('update user')) {
                    $user->assignRole(User::getAccountMeaning($user->account_type));
                    DB::commit();
                    return $this->getResponse(true, 200, '', 'User berhasil diupdate');
                } else {
                    return $this->getResponse(false, 505, '', 'Tidak mempunyai izin untuk aktifitas ini');
                }
            }
        }
    }

    public function updatePassword(PasswordRequest $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            $user = User::findOrFail($request->iduser);
            $user->password = $request->password;
            if (!$user->save()) {
                DB::rollBack();
                return $this->getResponse(true, 400, null, 'Password gagal diupdate');
            }
            if ($this->getUserPermission('change password')) {
                DB::commit();
                return $this->getResponse(true, 200, '', 'Password berhasil diupdate');
            } else {
                return $this->getResponse(false, 505, '', 'Tidak mempunyai izin untuk aktifitas ini');
            }
        }
    }
}
