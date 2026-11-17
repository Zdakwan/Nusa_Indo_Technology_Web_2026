<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ClientResetPasswordNotification extends ResetPassword
{
    protected function buildMailMessage($url)
    {
        $expire = config('auth.passwords.clients.expire', 60);

        return (new MailMessage)
            ->subject('Reset Password Akun Nusa Indo Tech')
            ->greeting('Halo!')
            ->line('Kami menerima permintaan untuk mereset password akun Nusa Indo Tech Anda.')
            ->action('Reset Password', $url)
            ->line("Link ini hanya berlaku selama {$expire} menit dan hanya bisa digunakan satu kali.")
            ->line('Jika Anda tidak merasa meminta reset password, abaikan email ini. Password Anda tidak akan berubah.')
            ->salutation('Salam, Tim Nusa Indo Tech');
    }
}