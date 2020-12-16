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
    
    <form method="post" action="{{ route('update-tugas', ['id_kelas'=>$id_kelas, 'id_feed'=>$id_feed, 'siswa_id'=>$siswa_id]) }}">

        @csrf
            <div class="form-group">
                <label>Kelas</label>
                <input type="text" class="form-control" value="{{ $nama_kelas }}" name="nama_kelas" disabled>
            </div>
            <div class="form-group">
                <label>Feed</label>
                <input type="text" class="form-control" value="{{ $nama_feed }}" name="feed_title" disabled>
            </div>
            @if($deadline != null)
                <div class="form-group">
                    <label>Deadline</label>
                    <input type="text" class="form-control" value="{{ date('d-m-Y',strtotime($deadline)) }}" name="deadline" disabled>
                </div>
            @endif
        <hr style="border-top: 1px solid #c6c6c6">

        @foreach($data_tugas as $dt)
            <div class="form-group">
                <input type="hidden" class="form-control" value="{{ $dt->id }}" name="id_tugas">
            </div>
            <div class="ui blue segment">
                <h5>
                    <a href="{{ url($nama_kelas.'/'.$nama_feed.'/'.User::where('id', $dt->siswa_id)->value('full_name').'/'.$dt->file) }}" target="_blank">
                        <img height"80" width="80" src="{{ asset('asset/file_thumb.png') }}"> {{ $dt->file }} </img>
                    </a>
                </h5>
            </div>
            <div class="form-group">
                <label>Nama Siswa</label>
                <input type="text" class="form-control" value="{{User::where('id', $dt->siswa_id)->value('full_name')}}" name="nama_siswa" disabled>
            </div>
            <div class="form-group">
                <label>Kelas</label>
                <input type="text" class="form-control" value="{{User::where('id', $dt->siswa_id)->value('kelas')}}" name="kelas" disabled>
            </div>
            <div class="form-group">
                <label>Angkatan</label>
                <input type="text" class="form-control" value="{{User::where('id', $dt->siswa_id)->value('angkatan')}}" name="angkatan" disabled>
            </div>
            <div class="form-group">
                <label>Tanggal Upload</label>
                <input type="text" class="form-control" value="{{date('d-m-Y h:i:s',strtotime($dt->created_at))}}" name="created_at" disabled>
            </div>
            <div class="form-group">
                <label>Nilai</label>
                <input type="text" class="form-control" value="{{$dt->nilai}}" name="nilai" min="0" max="100" placeholder="0-100">
                @if ($errors->has('nilai'))
                    <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('nilai') }}</p></div>
                @endif
            </div>
        @endforeach

        <div class="form-group">
            <button type="submit" class="ui huge inverted primary button"> NILAI TUGAS </button>
            <a href="{{ route('class-feed', ['id_kelas'=>$id_kelas, 'id_feed'=>$id_feed]) }}" class="ui huge button right floated"> KEMBALI </a>
        </div>
    </form>
@endsection

@section('modal')

@endsection

@push ('scripts')

@endpush