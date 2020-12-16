@extends('master')
 
@section('title', '')

@section('alert')

@endsection
 
@section('content')

	<?php 
		use Yajra\Datatables\Datatables; 
		use App\Model\User\User;
		use App\Model\StudentClass\StudentClass;

		// get user auth
		$user = Auth::user();
	?>

	@if($user->account_type == User::ACCOUNT_TYPE_TEACHER)
		<fieldset>
		<legend>Overview</legend>
			<div class="col-md-12">
				<div class="card">
					<div class="header">   
						<p style="text-align: center; font-weight: bold;"> Total Kelas Anda</p>
					</div>
					<div class="content">
						<h3 style="text-align: center;"> {{ $class }} </h3>
					</div>
				</div>
			</div>
		</fieldset>
		<br>
	@endif

	@if($user->account_type == User::ACCOUNT_TYPE_CREATOR || $user->account_type == User::ACCOUNT_TYPE_ADMIN)
		<fieldset>
		<legend>Overview</legend>
			<div class="col-md-4">
				<div class="card">
					<div class="header">   
						<p style="text-align: center; font-weight: bold;"> Total Kelas </p>
					</div>
					<div class="content">
						<h3 style="text-align: center;"> {{ $class }} </h3>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="header">   
						<p style="text-align: center; font-weight: bold;"> Total Siswa </p>
					</div>
					<div class="content">
					<h3 style="text-align: center;"> {{ $siswa }} </h3>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="header">   
						<p style="text-align: center; font-weight: bold;"> Total Guru </p>
					</div>
					<div class="content">
					<h3 style="text-align: center;"> {{ $teacher }} </h3>
					</div>
				</div>
			</div>
		</fieldset>
		<br>
	@endif

	<fieldset>
		<legend>Informasi User</legend>
		<div class="form-group">
			<label>Nama</label>
			<input disabled="true" type="text" class="form-control" value="{{ User::where('id', $id_user)->value('full_name') }}" name="user_type">
		</div>
		<div class="form-group">
			<label>Tipe User</label>
			<input disabled="true" type="text" class="form-control" value="{{ User::getAccountMeaning(Auth::user()->account_type) }}" name="user_type">
		</div>
		<div class="form-group">
			<label>Terakhir Login</label>
			<input disabled="true" type="text" class="form-control" value="{{ $last_login }}" name="last_login">
		</div>
	</fieldset>
	@if($user->account_type == User::ACCOUNT_TYPE_SISWA)
		<div class="ui raised segment">
			<legend>Kegiatan Mendatang</legend>
			<div class="ui relaxed divided list">
				@foreach($feed as $ff)
					@foreach($ff as $d)
						@if($d->deadline <= Carbon\Carbon::today()->addDays(7) && $d->deadline >= Carbon\Carbon::today())
							<div class="item" style="padding-bottom: 10px;">
								<div class="padding-all0">
									<h5 class="padding-list" style="font-weight: bold;">{{StudentClass::where('id', '=', $d->class_id)->value('class_name')}}</h5>
									<h6 style="margin: 0 0 5px 0; font-size: 15px; font-weight: normal; text-transform: none">{{$d->judul}}</h6>
									<div class="description">Hingga {{ date('d-m-Y',strtotime($d->deadline)) }}</div>
								</div>
							</div>
						@endif
					@endforeach
				@endforeach
			</div>
		</div>
	@endif

@endsection