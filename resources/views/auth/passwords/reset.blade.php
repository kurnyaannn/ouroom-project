@extends('login.indexlogin')

@section('title', '')

@section('alert')

@endsection

@section('content')

<div class="limiter">
    <div class="container-login">
        <div class="wrap-reset">
            <form method="POST" action="{{ route('password.reset') }}" class="email-form validate-form">

                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <span class="login100-form-title">
                    Reset Password
                </span>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="wrap-input100" data-validate="Valid email is required: ex@abc.xyz">
                        <input id="email" class="input100" name="email" type="email" value="{{ $email ?? old('email') }}" placeholder="Email">
                        <span class="focus-email"></span>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="wrap-input100">
                        <input class="input100" type="password" name="password" placeholder="Password" id="password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <div class="wrap-input100">
                        <input class="input100" type="password" name="password_confirmation" placeholder="Re-type Password" id="password_confirmation">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
                <div class="container-login100-form-btn">
                    <button type="submit" class="login100-form-btn">
                        GANTI
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection