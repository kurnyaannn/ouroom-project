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

	@foreach($data_kelas as $dk)
		@if($user->account_type == User::ACCOUNT_TYPE_CREATOR || $user->account_type == User::ACCOUNT_TYPE_ADMIN || $user->account_type == User::ACCOUNT_TYPE_TEACHER)
			<a class="ui big inverted primary button btn-edit" href="{{ route('siswa-class', ['id_kelas'=>$id_kelas]) }}">
				SISWA KELAS
			</a>
		@endif
		@if($user->account_type == User::ACCOUNT_TYPE_SISWA)
			<form action="{{ route('keluar-class', ['id_kelas'=>$id_kelas]) }}" method="POST">
				@csrf
				<button type="submit" class="ui big red button btn-edit">
					KELUAR KELAS
				</button>
			</form>
		@endif
			<h1 class="ui header class-attribute">
				{{$dk->class_name}}
				<div class="sub header sub-class-attribute">{{User::where('id', '=', $dk->teacher_id)->value('full_name')}}</div>
				<div class="sub header sub-class-attribute2">{{$dk->note}}</div>
				<button id="token" class="ui big button token" value="{{$dk->token}}" onclick="copyToken()" onmouseout="outFunc()"><span>{{$dk->token}}</span></button>
				@if($user->account_type == User::ACCOUNT_TYPE_SISWA)
					<a href="{{ route('rekap-tugas', ['id_kelas'=>$id_kelas]) }}" class="ui big inverted primary button btn-edit">
						REKAP TUGAS
					</a>
				@endif
			</h1>
	@endforeach
	<hr style="border-top: 1px solid #c6c6c6">
	@if($user->account_type == User::ACCOUNT_TYPE_CREATOR || $user->account_type == User::ACCOUNT_TYPE_ADMIN || $user->account_type == User::ACCOUNT_TYPE_TEACHER)
		<form method="POST" action="{{ route('upload-feed', ['id_kelas'=>$id_kelas]) }}" class="upload-container" enctype="multipart/form-data">

			@csrf

			<div id="customSegments" class="ui raised segments">
				<div class="ui segment">
					<a class="ui blue ribbon huge label">Tambahkan Sesuatu</a>
				</div>
				<div class="upload">
					<label>Judul</label>
					<input type="text" class="form-control" id="judul" name="judul">

					<input type="hidden" name="id_kelas" value="{{ $id_kelas }}">

					<label>Kategori</label>
					<select class="form-control" name="kategori">
						<option selected="true" disabled="disabled">Pilih Kategori</option> 
						<option value="Artikel">Artikel</option>
						<option value="Tugas">Tugas</option>
						<option value="Ujian">Ujian</option>
					</select>
				</div>
				<div class="ui segments">
					<textarea class="form-control" placeholder="Detail" rows="10" id="detail" name="detail"></textarea>
				</div>
				<div class="upload">
					<label>Upload File</label>
					<input type="file" class="form-control" id="file" name="file">

					<label>Tenggat Waktu</label>
					<input type="date" min="2020-01-01" class="form-control" id="deadline" name="deadline">
				</div>
				<div class="ui segments">
					<button type="submit" class="ui primary fluid bottom huge button">
						POST
					</button>
				</div>
			</div>
		</form>
	@endif
	<fieldset>

	<legend>{{ $nama_kelas }}</legend>
		@foreach ($data_feed as $df)
		<div id="customSegments" class="ui raised segment">
			<div class="top-attribute">
				@if($df->kategori == 'Artikel')
					<a class="ui green ribbon huge label">{{$df->kategori}}</a>
				@endif
				@if($df->kategori == 'Tugas')
					<a class="ui orange ribbon huge label">{{$df->kategori}}</a>
				@endif
				@if($df->kategori == 'Ujian')
					<a class="ui red ribbon huge label">{{$df->kategori}}</a>
				@endif
				<span class="judul">{{$df->judul}}</span>
				<a class="ui top right attached huge image label">
					<span class="date-post">{{ date('d-m-Y',strtotime($df->created_at)) }}</span>
				</a>
			</div>
			<pre class="detail-section padding-all0">{{$df->detail}}</pre>
			<a href="{{ route('class-feed', ['id_kelas'=>$id_kelas, 'id_feed'=>$df->id]) }}" class="ui bottom attached big button btnfeed">
				Lihat
			</a>
		</div>
		@endforeach
@endsection

@push('scripts')
<script type="text/javascript">
	function copyToken(elementID) {
		let element = document.getElementById("token");
		let elementText = element.textContent;
		copyText(elementText);
	}
	function copyText(text) {
		navigator.clipboard.writeText(text);
	}
</script>
@endpush