<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;
    protected $redirectTo = RouteServiceProvider::LOGIN;

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());
        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    protected function validationErrorMessages()
    {
        return [];
    }

    protected function credentials(Request $request)
    {
        return $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );
    }

    protected function resetPassword($user, $password)
    {
        $user->password = $password;
        $user->setRememberToken(Str::random(60));
        $user->save();
        event(new PasswordReset($user));
        $this->guard()->login($user);
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return redirect($this->redirectPath())
            ->with('status', trans($response));
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }

    public function broker()
    {
        return Password::broker();
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
