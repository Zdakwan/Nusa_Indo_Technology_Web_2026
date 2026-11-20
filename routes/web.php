<?php

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

// --- Controller Publik ---
use App\Http\Controllers\PublicController;

// --- Controller Client ---
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CallCenterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProyekClientController;

// --- Controller Admin ---
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\AdminProyekController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EnsiklopediaController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\LoginLogController;
use App\Http\Controllers\Admin\PelatihanController;
use App\Http\Controllers\Admin\PengaturanController as AdminPengaturanController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\PortofolioController;
use App\Http\Controllers\Admin\TentangKamiController;

/*
|--------------------------------------------------------------------------
| KONFIGURASI PEMBATASAN LOGIN & REGISTER (THROTTLE)
|--------------------------------------------------------------------------
*/
RateLimiter::for('admin-login', function (Request $request) {
    $email = strtolower(trim((string) $request->input('email', '')));
    $role = $email !== '' ? DB::table('admins')->where('email', $email)->value('role') : null;
    $limit = $role === 'super_admin' ? 3 : 5;

    return Limit::perMinute($limit)->by($email !== '' ? $email : $request->ip());
});

RateLimiter::for('client-auth', function (Request $request) {
    $identifier = strtolower(trim((string) ($request->input('login') ?? $request->input('email') ?? '')));

    return Limit::perMinute(7)->by($identifier !== '' ? $identifier : $request->ip());
});

/*
|--------------------------------------------------------------------------
| Halaman Peringatan Sesi Perangkat Aktif (Single-Device Enforcement)
|--------------------------------------------------------------------------
*/
Route::get('/session-locked', function () {
    if (! session()->has('device_lock_info')) {
        return redirect()->route('home');
    }

    return view('auth.device-locked', [
        'lockInfo' => session('device_lock_info'),
    ]);
})->name('auth.device-locked');

/*
|--------------------------------------------------------------------------
| Rute Admin (guard: admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Rute Guest Admin (Belum Login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])
            ->middleware('throttle:admin-login')
            ->name('login.process');
    });

    // Rute Auth Admin (Sudah Login)
    Route::middleware('auth:admin')->group(function () {
        // Logout tetap di luar single.device agar sesi selalu bisa dilepaskan dengan aman
        Route::match(['get', 'post'], '/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::middleware('single.device:admin')->group(function () {

            // ---- AKSES SEMUA ADMIN (Super Admin, Konten, Layanan) ----
            Route::middleware('admin.role:super_admin,admin_konten,admin_layanan')->group(function () {
                Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

                // Rute Tambah, Edit, dan Hapus Aktivitas Dashboard
                Route::post('/dashboard/aktivitas', [AdminDashboardController::class, 'storeAktivitas'])->name('dashboard.aktivitas.store');
                Route::put('/dashboard/aktivitas/{id}', [AdminDashboardController::class, 'updateAktivitas'])->name('dashboard.aktivitas.update');
                Route::delete('/dashboard/aktivitas/{id}', [AdminDashboardController::class, 'destroyAktivitas'])->name('dashboard.aktivitas.destroy');
                Route::delete('/dashboard/aktivitas', [AdminDashboardController::class, 'bulkDestroyAktivitas'])->name('dashboard.aktivitas.bulkDestroy');

                // Rute Pengaturan
                Route::get('/pengaturan', [AdminPengaturanController::class, 'index'])->name('pengaturan.index');
                Route::put('/pengaturan', [AdminPengaturanController::class, 'update'])->name('pengaturan.update');
            });

            // ---- AKSES SUPER ADMIN & ADMIN LAYANAN ----
            Route::middleware('admin.role:super_admin,admin_layanan')->group(function () {
                Route::get('/approval', [ApprovalController::class, 'index'])->name('approval.index');
                Route::put('/approval/{id}/status', [ApprovalController::class, 'updateStatus'])->name('approval.updateStatus');
                Route::put('/approval/call-center/{id}/status', [ApprovalController::class, 'updateCallCenterStatus'])->name('approval.call-center.updateStatus');

                // Route Hapus Massal & Resource Layanan
                Route::delete('/layanan/bulk-delete', [LayananController::class, 'bulkDelete'])->name('layanan.bulkDelete');
                Route::resource('layanan', LayananController::class)->only(['index', 'store', 'update', 'destroy']);

                Route::resource('client', ClientController::class)->only(['index', 'destroy']);

                // Rute Riwayat Login
                Route::get('/riwayat-login', [LoginLogController::class, 'index'])->name('riwayat-login.index');

                // === Manajemen Proyek ===
                Route::get('/proyek/{id}/pdf', [AdminProyekController::class, 'exportSinglePdf'])->name('proyek.single.pdf');
                Route::post('/proyek/{id}/progress', [AdminProyekController::class, 'updateProgress'])->name('proyek.updateProgress');
                Route::delete('/proyek/bulk-delete', [AdminProyekController::class, 'bulkDelete'])->name('proyek.bulkDelete');
                Route::resource('proyek', AdminProyekController::class);
            });

            // ---- AKSES SUPER ADMIN & ADMIN KONTEN ----
            Route::middleware('admin.role:super_admin,admin_konten')->group(function () {
                Route::delete('/portofolio/bulk-delete', [PortofolioController::class, 'bulkDelete'])->name('portofolio.bulkDelete');
                Route::resource('portofolio', PortofolioController::class)->only(['index', 'store', 'update', 'destroy']);

                Route::delete('/ensiklopedia/bulk-delete', [EnsiklopediaController::class, 'bulkDelete'])->name('ensiklopedia.bulkDelete');
                Route::resource('ensiklopedia', EnsiklopediaController::class)->only(['index', 'store', 'update', 'destroy']);

                Route::delete('/pelatihan/bulk-delete', [PelatihanController::class, 'bulkDelete'])->name('pelatihan.bulkDelete');
                Route::resource('pelatihan', PelatihanController::class)->only(['index', 'store', 'update', 'destroy']);

                // Rute Tentang Kami
                Route::get('/tentang-kami', [TentangKamiController::class, 'index'])->name('tentang-kami.index');
                Route::put('/tentang-kami/profil', [TentangKamiController::class, 'updateProfil'])->name('tentang-kami.profil.update');
                Route::post('/tentang-kami/tim', [TentangKamiController::class, 'storeTim'])->name('tentang-kami.tim.store');
                Route::put('/tentang-kami/tim/{id}', [TentangKamiController::class, 'updateTim'])->name('tentang-kami.tim.update');
                Route::delete('/tentang-kami/tim/{id}', [TentangKamiController::class, 'destroyTim'])->name('tentang-kami.tim.destroy');
            });

            // ---- AKSES KHUSUS SUPER ADMIN ----
            Route::middleware('admin.role:super_admin')->group(function () {
                Route::resource('pengguna', PenggunaController::class)->only(['index', 'store', 'destroy']);
            });
        });
    });
});

/*
|--------------------------------------------------------------------------
| Public Routes (Halaman Publik / Pengunjung)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/tentang-kami', [PublicController::class, 'tentangKami'])->name('tentang-kami');

Route::get('/layanan', [PublicController::class, 'layanan'])->name('layanan');
Route::get('/layanan/{id}', [PublicController::class, 'detailLayanan'])->name('layanan.detail');

Route::get('/portofolio', [PublicController::class, 'portofolio'])->name('portofolio.index');
Route::get('/portofolio/{id}', [PublicController::class, 'detailPortofolio'])->name('portofolio.detail');

Route::get('/ensiklopedia', [PublicController::class, 'ensiklopedia'])->name('ensiklopedia.index');
Route::get('/ensiklopedia/{id}', [PublicController::class, 'detailEnsiklopedia'])->name('ensiklopedia.detail');

Route::get('/pelatihan', [PublicController::class, 'pelatihan'])->name('pelatihan.index');
Route::get('/pelatihan/{id}', [PublicController::class, 'detailPelatihan'])->name('pelatihan.detail');

/*
|--------------------------------------------------------------------------
| Rute Client (guard: client)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:client')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:client-auth')
        ->name('login.process');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:client-auth')
        ->name('register.process');
});

Route::get('/lupa-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/lupa-password', [PasswordResetController::class, 'sendResetLink'])
    ->middleware('throttle:6,1')
    ->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

Route::middleware('auth:client')->group(function () {
    // Logout tetap di luar single.device agar sesi selalu bisa dilepaskan dengan aman
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('single.device:client')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/konsultasi/booking', [KonsultasiController::class, 'booking'])->name('konsultasi.booking');
        Route::post('/konsultasi/booking', [KonsultasiController::class, 'store'])->name('konsultasi.store');
        Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('konsultasi.index');
        Route::get('/konsultasi/{id}', [KonsultasiController::class, 'detail'])->name('konsultasi.detail');
        Route::get('/konsultasi/{id}/jadwal', [KonsultasiController::class, 'jadwal'])->name('konsultasi.jadwal');

        Route::get('/notifikasi', [KonsultasiController::class, 'notifikasi'])->name('notifikasi.index');
        Route::get('/notifikasi/{id}', [KonsultasiController::class, 'detailNotifikasi'])->name('notifikasi.detail');
        Route::post('/notifikasi/baca-semua', [KonsultasiController::class, 'tandaiSemuaDibaca'])->name('notifikasi.baca-semua');

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::put('/pengaturan/notifikasi', [PengaturanController::class, 'updateNotifikasi'])->name('pengaturan.notifikasi.update');
        Route::get('/pengaturan/faq', [FaqController::class, 'index'])->name('pengaturan.faq');
        Route::get('/pengaturan/panduan', [PanduanController::class, 'index'])->name('pengaturan.panduan');
        Route::get('/pengaturan/call-center', [CallCenterController::class, 'index'])->name('pengaturan.call-center');
        Route::post('/pengaturan/call-center', [CallCenterController::class, 'store'])->name('call-center.store');

        // Proyek Saya
        Route::get('/proyek', [ProyekClientController::class, 'index'])->name('proyek.index');
        Route::get('/proyek/{id}/pdf', [ProyekClientController::class, 'exportPdf'])->name('client.proyek.pdf');
        Route::get('/proyek/{id}', [ProyekClientController::class, 'show'])->name('proyek.show');
    });
});

// Endpoint ringan untuk memperbarui CSRF token agar form login tidak 419 saat lama dibuka
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
})->middleware('throttle:30,1')->name('csrf.refresh');
