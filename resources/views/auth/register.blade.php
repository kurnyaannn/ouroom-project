@extends('login.indexlogin')

@section('content')

<div class="limiter">
    <div class="container-register">
        <div class="wrap-register">
            <form method="POST" action="{{ route('register-siswa') }}" class="validate-form">

                @csrf

                <span class="register100-form-title">
                    ATM Classroom Register
                </span>
                <div class="wrap-input100">
                    <input class="inputRegister" type="text" name="full_name" placeholder="Nama Lengkap" id="full_name" value="{{ old('full_name') }}">
                    <span class="focus-input100"></span>
                </div>
                <div class="wrap-input100" data-validate="Valid email is required: ex@abc.xyz">
                    <input class="inputRegister" type="text" name="email" placeholder="Email" value="{{ old('email') }}">
                    <span class="focus-input100"></span>
                </div>
                <div class="wrap-input100">
                    <select class="form-control" name="jenis_kelamin" style="background-color: #e6e6e6; padding: 0 20px; border-radius: 0px; height: 48px">
                        <option selected="true" disabled="disabled">Jenis Kelamin</option> 
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="wrap-input100">
                    <select class="form-control" name="angkatan" style="background-color: #e6e6e6; padding: 0 20px; border-radius: 0px; height: 48px">
                        @foreach ($years as $year)
                            <option value="{{ $year }}"> {{ $year }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="wrap-input100">
                    <select class="form-control" name="kelas" style="background-color: #e6e6e6; padding: 0 20px; border-radius: 0px; height: 48px">
                        <option selected="true" disabled="disabled">Kelas</option> 
                        <option value="BDP">BDP</option>
                        <option value="UPW">UPW</option>
                        <option value="ATU">ATU</option>
                    </select>
                </div>
                <div class="wrap-input100">
                    <input class="input100" type="text" name="username" placeholder="Username" id="username" value="{{ old('username') }}">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100" type="password" name="password" placeholder="Password" id="password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100" type="password" name="password_confirmation" placeholder="Re-type Password" id="password_confirmation">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="container-login100-form-btn">
                    <a href="{{ route('register') }}">
                        <button class="login100-form-btn">
                            Register
                        </button>
                    </a>
                </div>
                <div class="text-center p-t-12">
                    @if($errors->any())
                        <p style="color: red">{{$errors->first()}}</p>
                    @endif
                    @if(session('success'))
                        <p style="color: blue">{{session('success')}}</p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@endsection