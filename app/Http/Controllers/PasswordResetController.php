<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        try {
            Password::broker('clients')->sendResetLink($request->only('email'));
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email gagal dikirim. Silakan coba lagi beberapa saat lagi.');
        }

        // Pesan sama untuk email terdaftar maupun tidak (mencegah tebak-tebakan email)
        return back()->with(
            'success',
            'Jika email tersebut terdaftar, link reset password telah dikirim. '
            . 'Periksa kotak masuk atau folder spam Anda. Jika belum menerima, tunggu 60 detik sebelum meminta lagi.'
        );
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
        ], [
            'email.required'     => 'Email wajib diisi.',
            'password.required'  => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal harus 8 karakter.',
            'password.regex'     => 'Password harus mengandung kombinasi huruf besar, huruf kecil, angka, dan karakter spesial (@$!%*#?&).',
        ]);

        $status = Password::broker('clients')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Client $client, string $password) {
                $client->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($client));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Link reset tidak valid atau sudah kedaluwarsa. Silakan minta link baru.']);
    }
}