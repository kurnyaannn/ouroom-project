@extends('master')

@section('title', '')

@section('alert')
    @if(Session::has('alert_success'))
        @component('components.alert')
            @slot('class')
                success
            @endslot
            @slot('title')
                Terimakasih
            @endslot
            @slot('message')
                {{ session('alert_success') }}
            @endslot
        @endcomponent
        @elseif(Session::has('alert_error'))
        @component('components.alert')
                @slot('class')
                    error
                @endslot
                @slot('title')
                    Cek Kembali
                @endslot
                @slot('message')
                    {{ session('alert_error') }}
                @endslot
        @endcomponent 
    @endif
@endsection

@section('content')
	<?php 
		use Yajra\Datatables\Datatables; 
        use App\Model\User\User;
        use Carbon\Carbon;

		// get user auth
		$user = Auth::user();
	?>

    <div style="padding-bottom: 20px">
        <a  href="{{ route('create-student-class') }}" type="button" class="btn btn-info custombtn"> TAMBAH </a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered data-table display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th style="text-align: center">Nama</th>
                    <th style="text-align: center">Tugas</th>
                    <th style="text-align: center">Nilai</th>
                    <th style="text-align: center" width="90px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection

@push ('scripts')
<link rel="stylesheet" type="text/css" href="<?= URL::to('/'); ?>/layout/assets/css/jquery.dataTables.css">

<script type="text/javascript" charset="utf8" src="<?= URL::to('/'); ?>/layout/assets/js/jquery.dataTables.js" defer></script>
<script type="text/javascript">
    function change() {
        if (confirm('Tandai Selesai Tugas Ini ?')) {
            var btn = document.getElementById("mark")
            btn.value = 'Selesai';
            btn.innerHTML = 'Selesai';
            btn.style.background = '#c0c1c2';
        } else {
            console.log('Thing was not saved to the database.');
        }
    }
</script>
@endpush