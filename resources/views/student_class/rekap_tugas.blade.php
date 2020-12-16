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
        use App\Model\StudentClass\Feed;
		use Carbon\Carbon;

		// get user auth
		$user = Auth::user();
    ?>

    <div class="table-responsive">
        <table id="rekap_table" class="table table-bordered data-table display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th style="text-align: center" width="40%">Feed</th>
                    <th style="text-align: center">Tugas</th>
                    <th style="text-align: center" width="100">Upload</th>
                    <th style="text-align: center" width="50">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data_tugas as $dt)
                    <tr>
                        <td>{{Feed::where('id', '=', $dt->feed_id)->value('judul')}}</td>
                        <td>{{$dt->file}}</td>
                        <td>{{date('d-m-Y',strtotime($dt->created_at))}}</td>
                        <td>{{$dt->nilai}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('modal')

@endsection

@push('scripts')
    <script type="text/javascript" charset="utf8" src="<?= URL::to('/'); ?>/layout/assets/js/datatables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#rekap_table').DataTable();
        });
    </script>
@endpush