<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Model\User\User;
use App\Model\StudentClass\StudentClass;
use App\Model\StudentClass\Feed;
use App\Http\Requests\StudentClass\StoreStudentClassRequest;
use App\Http\Requests\StudentClass\UpdateStudentClassRequest;
use App\Http\Resources\StudentClass\StudentClassResource;
use App\Http\Resources\StudentClass\StudentClassCollection;
use Auth;
use DB;
use Illuminate\Support\Facades\Log;

class StudentClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data_guru = User::getTeacher();
        $data_user = Auth::user()->full_name;
        $user_id = Auth::id();
        $guru_option = '<select class="js-example-basic-single form-control" name="teacher_id" id="guru" style="width: 100%">';
        foreach ($data_guru as $guru) {
            $guru_option .= '<option value="' . $guru->id . '">' . $guru->full_name . '</option>';
        }
        $guru_option .= '</select>';
        $years = array_combine(range(date("Y"), 2018), range(date("Y"), 2018));
        if ($this->getUserLogin()->account_type == User::ACCOUNT_TYPE_CREATOR || $this->getUserLogin()->account_type == User::ACCOUNT_TYPE_ADMIN) {
            if ($request->ajax()) {
                $data = StudentClass::all();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<button onclick="btnUbah(' . $row->id . ')" name="btnUbah" type="button" class="ui big inverted primary button" style="text-align: center"><span class="glyphicon glyphicon-edit"></span></button>';
                        return $btn;
                    })
                    ->addColumn('guru', function (StudentClass $class) {
                        if ($class->getTeacher->account_type != User::ACCOUNT_TYPE_TEACHER) {
                            return 'Guru sudah tidak aktif';
                        } else {
                            return $class->getTeacher->full_name;
                        }
                    })
                    ->rawColumns(['action'])
                    ->toJson();
            }
        } else if ($this->getUserLogin()->account_type == User::ACCOUNT_TYPE_TEACHER) {
            if ($request->ajax()) {
                $data = StudentClass::where('teacher_id', '=', $user_id)
                    ->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<button onclick="btnUbah(' . $row->id . ')" name="btnUbah" type="button" class="ui big inverted primary button" style="text-align: center"><span class="glyphicon glyphicon-edit"></span></button>';
                        return $btn;
                    })
                    ->addColumn('guru', function (StudentClass $class) {
                        return $class->getTeacher->full_name;
                    })
                    ->rawColumns(['action'])
                    ->toJson();
            }
        }

        if ($this->getUserPermission('index class')) {
            if ($this->getUserLogin()->account_type == User::ACCOUNT_TYPE_CREATOR || $this->getUserLogin()->account_type == User::ACCOUNT_TYPE_ADMIN) {
                $data_kelas = DB::table('tbl_class')
                    ->join('tbl_user', 'tbl_class.teacher_id', '=', 'tbl_user.id')
                    ->select('tbl_class.*', 'tbl_user.full_name')
                    ->get();
            } else if ($this->getUserLogin()->account_type == User::ACCOUNT_TYPE_TEACHER) {
                $data_kelas = DB::table('tbl_class')
                    ->join('tbl_user', 'tbl_class.teacher_id', '=', 'tbl_user.id')
                    ->where('teacher_id', $user_id)
                    ->select('tbl_class.*', 'tbl_user.full_name')
                    ->get();
            } else {
                $data_kelas = User::where('id', '=', $user_id)
                    ->with('hasClass')
                    ->get();
            }
            return view('student_class.index', ['active' => 'student_class', 'years' => $years, 'guru_option' => $guru_option, 'data_kelas' => $data_kelas, 'data_guru' => $data_guru, 'data_user' => $data_user]);
        } else {
            return view('error.unauthorized', ['active' => 'student_class']);
        }
    }

    public function joinClass(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
        $user = User::findOrFail(Auth::user()->id);
        $id_user = Auth::id();
        $token_kelas = $request->get('token');
        $user_data = DB::table('tbl_user')
            ->where('id', $id_user)
            ->select('angkatan', 'kelas')
            ->get();
        $class_data = DB::table('tbl_class')
            ->where('token', $token_kelas)
            ->select('angkatan', 'kelas')
            ->get();
        $id_kelas = DB::table('tbl_class')
            ->where('token', $token_kelas)
            ->value('id');
        if ($user_data == $class_data) {
            $user->hasClass()->syncWithoutDetaching($id_kelas);
            return redirect()->back()->with('alert_success', 'Berhasil Join Kelas');
        } else if ($id_kelas == null) {
            return redirect()->back()->with('alert_error', 'Token Kelas tidak valid.');
        }
        else {
            return redirect()->back()->with('alert_error', 'Hanya bisa join kelas dengan Kelas dan Angkatan yang sama.');
        }
    }

    public function create()
    {
        $user_id = Auth::id();
        if ($this->getUserPermission('create class')) {
            $years = array_combine(range(date("Y"), 2018), range(date("Y"), 2018));
            return view('student_class.store', ['active' => 'student_class', 'years' => $years, 'user_id' => $user_id]);
        } else {
            return view('error.unauthorized', ['active' => 'student_class']);
        }
    }

    public function update(UpdateStudentClassRequest $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            $student_class = StudentClass::findOrFail($request->get('idclass'));
            $student_class->class_name = $request->get('class_name');
            $student_class->angkatan = $request->get('angkatan');
            $student_class->kelas = $request->get('kelas');
            $student_class->note = $request->get('note');
            $student_class->teacher_id = $request->get('teacher_id');
            if (!$student_class->save()) {
                DB::rollBack();
                return $this->getResponse(false, 400, '', 'Kelas gagal diupdate');
            }
            if ($this->getUserPermission('update class')) {
                DB::commit();
                return $this->getResponse(true, 200, '', 'Kelas berhasil diupdate');
            } else {
                DB::rollBack();
                return $this->getResponse(false, 505, '', 'Tidak mempunyai izin untuk aktifitas ini');
            }
        }
    }

    public function store(StoreStudentClassRequest $request)
    {
        if ($this->getUserLogin()->account_type == User::ACCOUNT_TYPE_CREATOR || $this->getUserLogin()->account_type == User::ACCOUNT_TYPE_ADMIN) {
            DB::beginTransaction();
            $student_class = new StudentClass();
            $student_class->class_name = $request->get('class_name');
            $student_class->angkatan = $request->get('angkatan');
            $student_class->kelas = $request->get('kelas');
            $student_class->note = $request->get('note');
            $student_class->teacher_id = $request->get('teacher_id');
            $student_class->token = str_random(8);
        } else if ($this->getUserLogin()->account_type == User::ACCOUNT_TYPE_TEACHER) {
            DB::beginTransaction();
            $student_class = new StudentClass();
            $student_class->class_name = $request->get('class_name');
            $student_class->angkatan = $request->get('angkatan');
            $student_class->kelas = $request->get('kelas');
            $student_class->note = $request->get('note');
            $student_class->teacher_id = Auth::id();
            $student_class->token = str_random(8);
        }
        if (!$student_class->save()) {
            DB::rollBack();
            return redirect('student-class')->with('alert_error', 'Gagal Disimpan');
        }
        if ($this->getUserPermission('create class')) {
            DB::commit();
            return redirect('student-class')->with('alert_success', 'Berhasil Disimpan');
        } else {
            DB::rollBack();
            return $this->getResponse(false, 505, '', 'Tidak mempunyai izin untuk aktifitas ini');
        }
    }

    public function getUserTeacher(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('search')) {
                $data_guru = User::getTeacher($request->get('search'));
            } else {
                $data_guru = User::getTeacher();
            }
            $arr_data  = array();
            if ($data_guru) {
                $key = 0;
                foreach ($data_guru as $data) {
                    $arr_data[$key]['id'] = $data->id;
                    $arr_data[$key]['text'] = $data->full_name;
                    $key++;
                }
            }
            return json_encode($arr_data);
        }
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            $student_class = StudentClass::findOrFail($request->get('idclass'));
            return new StudentClassResource($student_class);
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            StudentClass::findOrFail($request->idclass)->delete();
            return $this->getResponse(true, 200, '', 'Kelas berhasil dihapus');
        }
        return redirect()->back()->with('alert_success', 'Berhasil Join Kelas');
    }
}
