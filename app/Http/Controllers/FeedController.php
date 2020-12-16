<?php

namespace App\Http\Controllers;

use App\Model\User\User;
use App\Model\StudentClass\StudentClass;
use App\Model\StudentClass\Feed;
use App\Model\StudentClass\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StudentClass\UpdateTugasRequest;
use App\Http\Requests\StudentClass\UpdateStudentClassRequest;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class FeedController extends Controller
{
    public function showClass(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        $user_id = $user->id;
        $role = User::where('id', '=', $user->id)->value('account_type');
        $id_kelas = $request->id_kelas;
        if ($role == 'Creator' || $role == 'Administrator') {
            $data_feed = DB::table('tbl_feed')
                ->join('tbl_class', 'tbl_feed.class_id', '=', 'tbl_class.id')
                ->where('tbl_class.id', $request->id_kelas)
                ->select('tbl_feed.*')
                ->get();
            $nama_kelas = DB::table('tbl_class')
                ->where('id', $id_kelas)
                ->value('class_name');
            $data_kelas = DB::table('tbl_class')
                ->where('id', $id_kelas)
                ->get();
            return view('student_class.list', ['active' => 'student_class', 'id_kelas' => $id_kelas, 'nama_kelas' => $nama_kelas, 'data_kelas' => $data_kelas, 'data_feed' => $data_feed]);
        } else if ($role == 'Guru') {
            $id_kelas = DB::table('tbl_class')
                ->where('id', $id_kelas)
                ->value('id');
            $validation = DB::table('tbl_class')
                ->where('id', $id_kelas)
                ->value('teacher_id');
            $checkUser = Auth::id();
            if ($validation == $checkUser) {
                $data_feed = DB::table('tbl_feed')
                    ->join('tbl_class', 'tbl_feed.class_id', '=', 'tbl_class.id')
                    ->where('tbl_class.id', $request->id_kelas)
                    ->select('tbl_feed.*')
                    ->get();
                $nama_kelas = DB::table('tbl_class')
                    ->where('id', $id_kelas)
                    ->value('class_name');
                $data_kelas = DB::table('tbl_class')
                    ->where('id', $id_kelas)
                    ->get();
                return view('student_class.list', ['active' => 'student_class', 'id_kelas' => $id_kelas, 'nama_kelas' => $nama_kelas, 'data_kelas' => $data_kelas, 'data_feed' => $data_feed]);
            } else {
                return view('error.unauthorized', ['active' => 'student_class']);
            }
        } else if ($role == 'Siswa') {
            $validation = DB::table('tbl_class')
                ->where('id', $id_kelas)
                ->value('token');
            $checkUser = User::where('id', '=', $user_id)
                ->first();
            $data_class = $checkUser->hasClass->where('token', '=', $validation)->first();
            if ($data_class != null) {
                $token = $data_class->token;
                if ($validation == $token) {
                    $data_feed = DB::table('tbl_feed')
                        ->join('tbl_class', 'tbl_feed.class_id', '=', 'tbl_class.id')
                        ->where('tbl_class.id', $request->id_kelas)
                        ->select('tbl_feed.*')
                        ->get();
                    $nama_kelas = DB::table('tbl_class')
                        ->where('id', $id_kelas)
                        ->value('class_name');
                    $data_kelas = DB::table('tbl_class')
                        ->where('id', $id_kelas)
                        ->get();
                    return view('student_class.list', ['active' => 'student_class', 'id_kelas' => $id_kelas, 'nama_kelas' => $nama_kelas, 'data_kelas' => $data_kelas, 'data_feed' => $data_feed]);
                } else if ($token == null) {
                    return view('error.unauthorized', ['active' => 'student_class']);
                }
            } else {
                return view('error.unauthorized', ['active' => 'student_class']);
            }
        }
    }

    public function keluarClass(Request $request)
    {
        $id_user = Auth::id();
        $id_kelas = $request->id;
        $user = User::findOrFail($id_user);
        $user->hasClass()->detach($id_kelas);
        $this->systemLog(false, 'Berhasil keluar kelas');
        return redirect()->back()->with('alert_success', 'Berhasil keluar kelas.');
    }

    public function rekapTugasSiswa(Request $request)
    {
        $id_kelas = $request->id_kelas;
        $id_siswa = $request->siswa_id;
        $data_tugas = DB::table('tbl_tugas')
            ->where('siswa_id', $id_siswa)
            ->where('class_id', $id_kelas)
            ->get();
        return view('student_class.tugas_siswa', ['active' => 'student_class', 'data_tugas' => $data_tugas]);
    }

    public function rekapTugasClass(Request $request)
    {
        $id_user = Auth::id();
        $id_kelas = $request->id_kelas;
        $data_tugas = DB::table('tbl_tugas')
            ->where('siswa_id', $id_user)
            ->where('class_id', $id_kelas)
            ->get();
        return view('student_class.rekap_tugas', ['active' => 'student_class', 'data_tugas' => $data_tugas]);
    }

    public function showFeed(Request $request)
    {
        $id_kelas = $request->id_kelas;
        $id_feed = $request->id_feed;
        $siswa_id = Auth::id();
        $nama_kelas = DB::table('tbl_class')
            ->where('id', $id_kelas)
            ->value('class_name');
        $feed = DB::table('tbl_feed')
            ->where('id', $id_feed)
            ->select('*')
            ->get();
        $data_tugas = DB::table('tbl_tugas')
            ->where('feed_id', $id_feed)
            ->get();
        $nilai_tugas = DB::table('tbl_tugas')
            ->where('siswa_id', $siswa_id)
            ->where('feed_id', $id_feed)
            ->value('nilai');
        $tugas = DB::table('tbl_tugas')
            ->where('feed_id', $id_feed)
            ->where('siswa_id', $siswa_id)
            ->value('file');
        return view('student_class.feed', ['active' => 'student_class', 'id_kelas' => $id_kelas, 'nama_kelas' => $nama_kelas, 'id_feed' => $id_feed, 'feed' => $feed, 'data_tugas' => $data_tugas, 'tugas' => $tugas, 'nilai' => $nilai_tugas]);
    }

    public function showTugas(Request $request)
    {
        $id_kelas = $request->id_kelas;
        $id_feed = $request->id_feed;
        $siswa_id = $request->siswa_id;
        $nama_feed = DB::table('tbl_feed')
            ->where('id', $id_feed)
            ->value('judul');
        $nama_kelas = DB::table('tbl_class')
            ->where('id', $id_kelas)
            ->value('class_name');
        $deadline = DB::table('tbl_feed')
            ->where('id', $id_feed)
            ->value('deadline');
        $data_tugas = DB::table('tbl_tugas')
            ->where('siswa_id', $siswa_id)
            ->where('feed_id', $id_feed)
            ->get();
        return view('student_class.assessment', ['active' => 'student_class', 'id_kelas' => $id_kelas, 'nama_kelas' => $nama_kelas, 'deadline' => $deadline, 'id_feed' => $id_feed, 'nama_feed' => $nama_feed, 'siswa_id' => $siswa_id, 'data_tugas' => $data_tugas]);
    }

    public function showSiswaClass(Request $request)
    {
        $id_kelas = $request->id_kelas;
        $years = array_combine(range(date("Y"), 2018), range(date("Y"), 2018));
        $data_kelas = StudentClass::where('id', '=', $id_kelas)
            ->get();
        if ($request->ajax()) {
            $data = StudentClass::where('id', '=', $id_kelas)
                ->with('hasUser')
                ->get();
            foreach ($data as $du) {
                return Datatables::of($du->hasUser)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $tugas = '<button onclick="btnTgs(' . $row->id . ')" name="btnTgs" type="button" class="ui big inverted primary button"><span class="glyphicon glyphicon-file"></span></button>';
                        $delete = '<button onclick="btnDel(' . $row->id . ')" name="btnDel" type="button" class="ui big red button"><span class="glyphicon glyphicon-trash"></span></button>';
                        return $tugas . '&nbsp' .$delete;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        }
        if ($this->getUserPermission('index class')) {
            return view('student_class.siswa_class', ['active' => 'student_class', 'years' => $years, 'id_kelas' => $id_kelas, 'data_kelas' => $data_kelas]);
        } else {
            return view('error.unauthorized', ['active' => 'student_class']);
        }
    }

    public function deleteSiswaClass(Request $request)
    {
        $id_kelas = $request->id_kelas;
        if ($request->ajax()) {
            $user = User::findOrFail($request->iduser);
            $user->hasClass()->detach($id_kelas);
            $this->systemLog(false, 'Berhasil menghapus user');
            return $this->getResponse(true, 200, '', 'User berhasil dihapus');
        }
    }

    public function uploadFeed(Request $request)
    {
        $this->validate($request, [
            'judul' => 'required',
            'kategori' => 'required',
            'detail' => 'required',
            'file' => 'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048',
        ]);

        if ($request->has('file')) {
            $id = $request->id_kelas;
            $class_name = StudentClass::where('id', '=', $id)->value('class_name');
            $feed = new Feed();
            $feed->judul = $request->get('judul');
            $feed->kategori = $request->get('kategori');
            $feed->detail = $request->get('detail');
            $files = $request->file('file');
            $path = public_path($class_name . '/' . $feed->judul);
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            $files_name = $files->getClientOriginalName();
            $files->move($path, $files_name);
            $feed->file = $files_name;
            $feed->deadline = $request->get('deadline');
            $feed->class_id = $request->get('id_kelas');
        } else {
            $feed = new Feed();
            $feed->judul = $request->get('judul');
            $feed->kategori = $request->get('kategori');
            $feed->detail = $request->get('detail');
            $feed->deadline = $request->get('deadline');
            $feed->class_id = $request->get('id_kelas');
        }
        $feed->save();
        if (!$feed->save()) {
            return redirect()->back()->with('alert_error', 'Gagal Disimpan');
        } else {
            return redirect()->back()->with('alert_success', 'Data Berhasil Disimpan');
        }
    }

    public function uploadTugas(Request $request)
    {
        $this->validate($request, [
            'file' => 'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048',
        ]);

        $id_siswa = Auth::id();
        $nama_siswa = User::where('id', '=', $id_siswa)
            ->value('full_name');
        $nama_kelas = $request->nama_kelas;
        $nama_feed = $request->nama_feed;
        $id_class = $request->id_kelas;
        $id_feed = $request->id_feed;
        if ($request->has('file')) {
            $tugas = new Tugas();
            $files = $request->file('file');
            $path = public_path($nama_kelas . '/' . $nama_feed . '/' . $nama_siswa);
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            $files_name = $files->getClientOriginalName();
            $files->move($path, $files_name);
            $tugas->file = $files_name;
            $tugas->siswa_id = $id_siswa;
            $tugas->class_id = $id_class;
            $tugas->feed_id = $id_feed;
            $tugas->save();
            return redirect()->back()->with('alert_success', 'Data Berhasil Disimpan');
        } else {
            return redirect()->back()->with('alert_error', 'Gagal Disimpan');
        }
    }

    public function updateTugas(UpdateTugasRequest $request)
    {
        $this->validate($request, [
            'nilai' => 'integer',
        ]);

        $siswa_id = $request->siswa_id;
        $feed_id = $request->id_feed;
        $class_id = $request->id_kelas;
        DB::table('tbl_tugas')
            ->where('siswa_id', $siswa_id)
            ->where('class_id', $class_id)
            ->where('feed_id', $feed_id)
            ->update([
                'nilai' => $request->nilai
        ]);
        // dd($class_id);
        return redirect()->back()->with('alert_success', 'Data Berhasil Disimpan');
    }

    public function updateClass(Request $request)
    {
        $id = $request->class_name;
        dd($id);
        DB::table('tbl_class')->where('id', $id)->update([
            'class_name' => $request->class_name,
            'angkatan' => $request->angkatan,
            'kelas' => $request->kelas,
            'note' => $request->note
        ]);
        return redirect()->back()->with('alert_success', 'Data Berhasil Disimpan');
    }

    public function updateFeed(Request $request)
    {
        $id_feed = $request->id_feed;
        if ($request->has('file')) {
            $id = $request->id_kelas;
            $class_name = StudentClass::where('id', '=', $id)->value('class_name');
            $files = $request->file('file');
            $files_name = $files->getClientOriginalName();
            DB::table('tbl_feed')->where('id', $id_feed)->update([
                'judul' => $request->judul,
                'kategori' => $request->kategori,
                'detail' => $request->detail,
                'file' => $files_name,
                'deadline' => $request->deadline
            ]);
            $path = public_path($class_name . '/' . $request->judul);
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            $files->move($path, $files_name);
        } else {
            DB::table('tbl_feed')->where('id', $id_feed)->update([
                'judul' => $request->judul,
                'kategori' => $request->kategori,
                'detail' => $request->detail,
                'deadline' => $request->deadline
            ]);
        }
        return redirect()->back()->with('alert_success', 'Data Berhasil Disimpan');
    }

    public function deleteFeed($id_kelas, $id)
    {
        DB::table('tbl_feed')->where('id', $id)->delete();
        return redirect()->action(
            'FeedController@showClass',
            ['id_kelas' => $id_kelas]
        )->with('alert_success', 'Feed Berhasil Dihapus');
    }
}
