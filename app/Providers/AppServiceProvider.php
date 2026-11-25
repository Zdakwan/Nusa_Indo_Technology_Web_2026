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
    }
}