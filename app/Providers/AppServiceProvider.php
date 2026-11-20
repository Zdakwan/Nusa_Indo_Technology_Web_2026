<?php

namespace App\Providers;

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
        View::composer(['admin.dashboard', 'admin.dashboard.*'], AdminDashboardChartComposer::class);
        Event::listen(Login::class, HandleSingleDeviceLogin::class);
        Event::listen(Logout::class, HandleSingleDeviceLogout::class);
        //
    }
}