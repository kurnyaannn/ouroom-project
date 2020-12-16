<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Auth\Notifications\ResetPassword;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }
        return (new MailMessage)
            ->line('Kami telah menerima permintaan reset password dari akun anda.')
            ->action('Reset Password', route('password.reset.token', ['token' => $this->token]))
            ->line('Abaikan pesan ini apabila anda tidak melakukan permintaan ini.')
            ->subject('Permintaan perubahan Password');
    }
}
