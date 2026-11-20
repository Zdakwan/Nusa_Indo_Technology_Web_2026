<?php

use App\Http\Middleware\AdminRole;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\EnsureSingleDeviceSession;
use App\Http\Middleware\VerifyMobileApiKey;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->append(SecurityHeaders::class);

        $middleware->alias([
            'admin.role'     => AdminRole::class,
            'mobile.api.key' => VerifyMobileApiKey::class,
            'single.device'  => EnsureSingleDeviceSession::class,
        ]);

        $middleware->redirectGuestsTo(fn (Request $request) =>
            $request->is('admin/*') ? route('admin.login') : route('login')
        );

        $middleware->redirectUsersTo(fn (Request $request) =>
            Auth::guard('admin')->check() ? route('admin.dashboard') : route('dashboard')
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (\Symfony\Component\HttpFoundation\Response $response) {
            if ($response->getStatusCode() === 419) {
                return back()
                    ->withInput(request()->except(['password', 'password_confirmation', '_token']))
                    ->withErrors(['email' => 'Sesi halaman telah diperbarui. Silakan klik login kembali.']);
            }
            return $response;
        });
        $exceptions->shouldRenderJsonWhen(fn (Request $request, \Throwable $e) =>
            $request->is('api/*') || $request->expectsJson()
        );
    })
    ->create();

$app->useStoragePath($_ENV['APP_STORAGE'] ?? base_path('storage'));

return $app;
