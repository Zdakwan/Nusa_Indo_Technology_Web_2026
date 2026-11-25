<?php

namespace App\Providers;




use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use App\View\Composers\AdminDashboardChartComposer;
use Illuminate\Support\Facades\View;

use App\Listeners\HandleSingleDeviceLogin;
use App\Listeners\HandleSingleDeviceLogout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;

use Illuminate\Support\ServiceProvider;
// Tambahkan 4 baris "use" di bawah ini agar class terdeteksi
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use App\Models\LoginLog;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
<<<<<<< HEAD
        RateLimiter::for('register', function (Request $request) {
            $ip = $request->ip();

            return [
                Limit::perMinute(3)->by('reg-min:' . $ip)->response(function () {
                    return back()->withErrors([
                        'email' => 'Terlalu banyak percobaan pendaftaran. Silakan tunggu 1 menit.',
                    ])->withInput();
                }),
                Limit::perHour(10)->by('reg-hour:' . $ip)->response(function () {
                    return back()->withErrors([
                        'email' => 'Batas pendaftaran dari jaringan Anda tercapai. Coba lagi nanti.',
                    ])->withInput();
                }),
            ];
        });

        View::composer(['admin.dashboard', 'admin.dashboard.*'], AdminDashboardChartComposer::class);
        Event::listen(Login::class, HandleSingleDeviceLogin::class);
        Event::listen(Logout::class, HandleSingleDeviceLogout::class);
        //
=======
        // PEREKAM OTOMATIS RIWAYAT LOGIN
        Event::listen(function (Login $event) {
            $user = $event->user;
            
            // Cek apakah ini admin (super_admin/admin_layanan) atau client
            $role = $event->guard === 'admin' ? ($user->role ?? 'Admin') : 'Client';

            LoginLog::create([
                'nama'       => $user->name ?? $user->company ?? 'Pengguna',
                'email'      => $user->email,
                'role'       => $role,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        });
>>>>>>> mey
    }
}