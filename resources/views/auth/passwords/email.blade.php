@extends('login.indexlogin')

@section('title', '')

@section('alert')

@endsection

@section('content')

<div class="limiter">
    <div class="container-login">
        <div class="wrap-reset">
            <form method="POST" action="{{ route('password.email') }}" class="email-form validate-form" autocomplete="off">

                @csrf

                <span class="login100-form-title">
                    Reset Password
                </span>
                <div class="wrap-input100" data-validate="Valid email is required: ex@abc.xyz">
                <input id="email" class="input100" name="email" placeholder="Email" class="form-control"  type="email">
                    <span class="focus-email"></span>
                </div>
                <div class="container-login100-form-btn">
                    <button type="submit" class="login100-form-btn">
                        Submit
                    </button>
                </div>
                <div class="text-center p-t-12" role="alert">
                    @if(session('status'))
                        <p style="color: blue">Link reset password telah dikirim. Silahkan cek Email anda.</p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@endsection