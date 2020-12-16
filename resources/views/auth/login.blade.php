@extends('login.indexlogin')

@section('title', '')

@section('alert')

@endsection

@section('content')

<div class="limiter">
    <div class="container-login">
        <div class="wrap-login">
            <div class="login100-pic js-tilt" data-tilt>
                <img src="<?= URL::to('/layout_login/images/undraw.png') ?>" alt="IMG">
            </div>
            <form method="POST" action="{{ route('login') }}" class="login100-form validate-form">

                @csrf

                <span class="login100-form-title">
                    Ouroom
                </span>
                <div class="wrap-input100" data-validate="Valid email is required: ex@abc.xyz">
                    <input class="input100" type="text" name="username" placeholder="Username" id="username">
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
                <div class="container-login100-form-btn">
                    <button type="submit" class="login100-form-btn">
                        Login
                    </button>
                </div>
                <div class="container-login100-form-btn">
                    <a href="{{ route('register') }}">
                        Register
                    </a>
                </div>
                <div class="text-center p-t-12">
                    <span class="txt1">
                        Lupa
                    </span>
                    <a class="txt2" href="{{ route('show-reset') }}">
                        Password ?
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