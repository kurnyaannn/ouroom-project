<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Profile\PasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\Profile\ProfileRequest;
use App\Model\User\User;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Http\Requests\User\StoreUserRequest;
use DB;
use URL;
use Image;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data_user = Auth::user();
        if ($this->getUserPermission('index profile')) {
            $this->systemLog(false, 'Mengakses halaman Profile');
            return view('profile.index', ['active' => 'profile', 'data_user' => $data_user]);
        } else {
            $this->systemLog(true, 'Gagal Mengakses halaman Profile');
            return view('error.unauthorized', ['active' => 'profile']);
        }
    }

    public function update(ProfileRequest $request)
    {
        $this->validate($request, [
            'file' => 'mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($request->hasFile('file')) {
            DB::beginTransaction();
            $user = User::findOrFail(Auth::user()->id);
            $user->email = $request->get('email');
            $user->full_name = $request->get('full_name');
            $user->account_type = $user->account_type;
            $files = $request->file('file');
            $path = public_path('asset_user' . '/' . $user->account_type . '/' . $user->full_name);
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            $files_name = $files->getClientOriginalName();
            $files->move($path, $files_name);
            $user->profile_picture = $files_name;
        } else {
            DB::beginTransaction();
            $user = User::findOrFail(Auth::user()->id);
            $user->email = $request->get('email');
            $user->full_name = $request->get('full_name');
            $user->account_type = $user->account_type;
        }
        if (!$user->save()) {
            $this->systemLog(true, 'Gagal mengupdate Profile');
            DB::rollBack();
            return redirect('profile')->with('alert_error', 'Gagal Disimpan');
        }
        if ($this->getUserPermission('update profile')) {
            $this->systemLog(false, 'Berhasil mengupdate Profile');
            DB::commit();
            return redirect('profile')->with('alert_success', 'Berhasil Disimpan');
        } else {
            $this->systemLog(true, 'Gagal mengupdate Profile');
            DB::rollBack();
            return view('error.unauthorized', ['active' => 'profile']);
        }
    }

    public function updatePassword(PasswordRequest $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            if (User::passwordChangeValidation($request->get('old_password'), Auth::user()->password)) {
                $user = User::findOrFail(Auth::user()->id);
                $user->password = $request->get('password');
                if (!$user->save()) {
                    $this->systemLog(true, 'Gagal mengupdate Password');
                    DB::rollBack();
                    return $this->getResponse(false, 400, null, 'Password gagal diupdate');
                }
                if ($this->getUserPermission('change password')) {
                    $this->systemLog(false, 'Berhasil mengupdate Password');
                    DB::commit();
                    return $this->getResponse(true, 200, '', 'Password berhasil diupdate');
                } else {
                    $this->systemLog(true, 'Gagal mengupdate Password');
                    DB::rollBack();
                    return $this->getResponse(false, 505, '', 'Tidak mempunyai izin untuk aktifitas ini');
                }
            }
            DB::rollBack();
            return $this->getResponse(false, 400, null, 'Password lama yang anda masukkan tidak sesuai');
        }
    }

    public function deleteImage(Request $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            $user_data = User::findOrFail(Auth::user()->id);
            $picture_backup = $user_data->profile_picture;
            $user_data->profile_picture = null;
            if ($this->getUserPermission('update profile')) {
                if ($user_data->save()) {
                    $this->systemLog(false, 'Berhasil memperbaharui gambar profile');
                    Storage::disk('public_uploads')->delete('profile/' . $picture_backup);
                    DB::commit();
                    return $this->getResponse(true, 200, '', 'Gambar berhasil dihapus');
                }
            } else {
                return $this->getResponse(false, 505, '', 'Tidak mempunyai izin untuk aktifitas ini');
            }
            $this->systemLog(true, 'Gagal memperbaharui gambar profile');
            DB::rollBack();
            return $this->getResponse(false, 400, '', 'Gambar gagal dihapus');
        }
    }
}
