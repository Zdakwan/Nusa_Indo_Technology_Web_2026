<?php

namespace App\Providers;

use App\Listeners\HandleSingleDeviceLogin;
use App\Listeners\HandleSingleDeviceLogout;
use App\View\Composers\AdminDashboardChartComposer;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Rate limiter login admin (membedakan GET halaman & POST autentikasi agar tidak redirect loop)
        RateLimiter::for('admin-login', function (Request $request) {
            $ip = (string) $request->ip();

            if ($request->isMethod('get')) {
                return Limit::perMinute(30)->by('admin-view:' . $ip);
            }

            $identifier = strtolower(trim((string) $request->input('email', $request->input('username', 'guest'))));

            return [
                Limit::perMinute(5)->by('admin-auth:' . $identifier . '|' . $ip)->response(function () use ($request) {
                    return back()->withErrors([
                        'email' => 'Terlalu banyak percobaan login admin. Silakan tunggu 1 menit.',
                    ])->withInput($request->except('password'));
                }),
                Limit::perMinute(15)->by('admin-ip:' . $ip)->response(function () {
                    return back()->withErrors([
                        'email' => 'Terlalu banyak aktivitas login dari jaringan Anda. Coba lagi nanti.',
                    ]);
                }),
            ];
        });

        // Rate limiter autentikasi client
        RateLimiter::for('client-auth', function (Request $request) {
            $ip = (string) $request->ip();

            if ($request->isMethod('get')) {
                return Limit::perMinute(30)->by('client-view:' . $ip);
            }

            $email = strtolower(trim((string) $request->input('email', 'guest')));

            return [
                Limit::perMinute(5)->by('client-auth:' . $email . '|' . $ip)->response(function () use ($request) {
                    return back()->withErrors([
                        'email' => 'Terlalu banyak percobaan login. Silakan tunggu 1 menit.',
                    ])->withInput($request->except('password'));
                }),
                Limit::perMinute(20)->by('client-ip:' . $ip)->response(function () {
                    return back()->withErrors([
                        'email' => 'Batas percobaan login dari jaringan Anda tercapai. Coba lagi nanti.',
                    ]);
                }),
            ];
        });

        // Rate limiter registrasi client
        RateLimiter::for('register', function (Request $request) {
            $ip = (string) $request->ip();

            return [
                Limit::perMinute(3)->by('reg-min:' . $ip)->response(function () use ($request) {
                    return back()->withErrors([
                        'email' => 'Terlalu banyak percobaan pendaftaran. Silakan tunggu 1 menit.',
                    ])->withInput($request->except('password'));
                }),
                Limit::perHour(10)->by('reg-hour:' . $ip)->response(function () use ($request) {
                    return back()->withErrors([
                        'email' => 'Batas pendaftaran dari jaringan Anda tercapai. Coba lagi nanti.',
                    ])->withInput($request->except('password'));
                }),
            ];
        });

        View::composer(['admin.dashboard', 'admin.dashboard.*'], AdminDashboardChartComposer::class);
        Event::listen(Login::class, HandleSingleDeviceLogin::class);
        Event::listen(Logout::class, HandleSingleDeviceLogout::class);
    }
}
