<?php 
    use Yajra\Datatables\Datatables; 
    use App\Model\User\User;

    // get user auth
    $user = Auth::user();
?>

<div class="sidebar-wrapper">
    <div class="logo">
        <img src="<?= URL::to('/layout_login/images/logo fix.png'); ?>" style="width:40px;height:40px;" class="center">
    </div>
    <ul class="nav">
        <li class="<?= $active == 'home' ? 'active' : '' ?>">
            <a href="<?= URL::to('/'); ?>">
                <i class="pe-7s-home"></i>
                <p>Home</p>
            </a>
        </li>

        @if($user->account_type == User::ACCOUNT_TYPE_CREATOR || $user->account_type == User::ACCOUNT_TYPE_ADMIN)
            <li class="<?= $active == 'user' ? 'active' : '' ?>">
                <a href="<?= URL::to('/user'); ?>">
                    <i class="pe-7s-user"></i>
                    <p>Pengguna</p>
                </a>
            </li>
            <li class="<?= $active == 'student_class' ? 'active' : '' ?>">
                <a href="<?= URL::to('/student-class'); ?>">
                    <i class="pe-7s-study"></i>
                    <p>Kelas</p>
                </a>
            </li>
            <li class="<?= $active == 'siswa' ? 'active' : '' ?>">
                <a href="<?= URL::to('/siswa'); ?>">
                    <i class="pe-7s-id"></i>
                    <p>Siswa</p>
                </a>
            </li>
        @endif

        @if($user->account_type == User::ACCOUNT_TYPE_TEACHER)
            <li class="<?= $active == 'student_class' ? 'active' : '' ?>">
                <a href="<?= URL::to('/student-class'); ?>">
                    <i class="pe-7s-study"></i>
                    <p>Kelas</p>
                </a>
            </li>
        @endif

        @if($user->account_type == User::ACCOUNT_TYPE_SISWA)
            <li class="<?= $active == 'student_class' ? 'active' : '' ?>">
                <a href="<?= URL::to('/student-class'); ?>">
                    <i class="pe-7s-study"></i>
                    <p>Kelas</p>
                </a>
            </li>
        @endif
    </ul>
</div>

<style type="text/css">

.center 
{
    display: block;
    margin-left: auto;
    margin-right: auto;
}

</style>

@push('scripts')


@endpush


@section('modal')
    <div class="modal fade" id="detailNotification" role="dialog" >
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <p class="modal-title">Detail Notifikasi</p>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul Notifikasi</label>
                        <input disabled="true" type="text" class="form-control" value="" name="notification_title" id="notification_title" style="color: black;">
                    </div>
                    <div class="form-group">
                        <label>Isi Pesan Notifikasi</label>
                        <input disabled="true" type="text" class="form-control" value="" name="notification_message" id="notification_message" style="color: black;">
                    </div>
                    <div class="form-group">
                        <label>Dikeluarkan Pada Tanggal</label>
                        <input disabled="true" type="text" class="form-control" value="" name="date" id="date" style="color: black;">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection