<?php

/*
|==========================================================================
| FILE: bootstrap/app.php
|==========================================================================
| FUNGSI  : Titik awal konfigurasi seluruh aplikasi Laravel.
|           Di sini kamu mendaftarkan:
|             1. Lokasi file route (web, api, console)
|             2. Middleware alias (nama pendek untuk middleware class)
|             3. Redirect otomatis saat login/guest
|             4. Penanganan error global
|
| CARA EDIT:
|   - Tambah middleware baru? → tambahkan alias di $middleware->alias([...])
|   - Ubah redirect setelah login? → edit bagian redirectUsersTo / redirectGuestsTo
|   - Ubah penanganan error (mis. 419)? → edit blok withExceptions
|==========================================================================
*/

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
        // ─── Daftar file route yang digunakan ───────────────────────────
        // Tambah file route baru? Daftarkan di sini.
        web: __DIR__.'/../routes/web.php',       // Route halaman web (browser)
        api: __DIR__.'/../routes/api.php',       // Route API (JSON / mobile)
        commands: __DIR__.'/../routes/console.php', // Perintah artisan kustom
        health: '/up',                           // Endpoint cek kesehatan server
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ─── Percayai semua proxy (penting untuk Vercel/CDN) ────────────
        $middleware->trustProxies(at: '*');

        // ─── Jalankan SecurityHeaders di setiap request ─────────────────
        // File: app/Http/Middleware/SecurityHeaders.php
        // Fungsi: Tambah header keamanan (X-Frame-Options, HSTS, dll)
        $middleware->append(SecurityHeaders::class);

        // ─── Daftarkan alias middleware ──────────────────────────────────
        // Alias ini dipakai di routes/web.php dengan ->middleware('alias')
        //
        // Cara pakai di route:
        //   ->middleware('admin.role:super_admin,admin_layanan')
        //   ->middleware('single.device:admin')
        //   ->middleware('mobile.api.key')
        $middleware->alias([
            'admin.role'     => AdminRole::class,              // Cek role admin
            'mobile.api.key' => VerifyMobileApiKey::class,    // Cek API key mobile
            'single.device'  => EnsureSingleDeviceSession::class, // Paksa 1 perangkat
        ]);

        // ─── Redirect jika belum login (guest) ──────────────────────────
        // Jika akses /admin/* → arahkan ke halaman login admin
        // Jika akses route lain → arahkan ke halaman login client
        $middleware->redirectGuestsTo(fn (Request $request) =>
            $request->is('admin/*') ? route('admin.login') : route('login')
        );

        // ─── Redirect jika sudah login (auth) ───────────────────────────
        // Jika sudah login sebagai admin → arahkan ke dashboard admin
        // Jika sudah login sebagai client → arahkan ke dashboard client
        $middleware->redirectUsersTo(fn (Request $request) =>
            Auth::guard('admin')->check() ? route('admin.dashboard') : route('dashboard')
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // ─── Tangani error 419 (CSRF token expired) ─────────────────────
        // Error ini muncul jika form dibiarkan terlalu lama terbuka.
        // Alih-alih halaman error kosong, kita kembalikan ke form dengan pesan.
        $exceptions->respond(function (\Symfony\Component\HttpFoundation\Response $response) {
            if ($response->getStatusCode() === 419) {
                return back()
                    ->withInput(request()->except(['password', 'password_confirmation', '_token']))
                    ->withErrors(['email' => 'Sesi halaman telah diperbarui. Silakan klik login kembali.']);
            }
            return $response;
        });

        // ─── Render JSON untuk request API ──────────────────────────────
        // Jika request berasal dari /api/* atau Accept: application/json,
        // error dikembalikan sebagai JSON bukan HTML.
        $exceptions->shouldRenderJsonWhen(fn (Request $request, \Throwable $e) =>
            $request->is('api/*') || $request->expectsJson()
        );
    })
    ->create();

// ─── Lokasi folder storage ───────────────────────────────────────────────
// Bisa dikonfigurasi lewat env APP_STORAGE, default ke folder storage/ biasa
$app->useStoragePath($_ENV['APP_STORAGE'] ?? base_path('storage'));

return $app;
