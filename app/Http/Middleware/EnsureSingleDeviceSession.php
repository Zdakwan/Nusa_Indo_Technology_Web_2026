<?php

/*
|==========================================================================
| FILE: app/Http/Middleware/EnsureSingleDeviceSession.php
|==========================================================================
| FUNGSI  : Memastikan satu akun hanya bisa login di SATU perangkat
|           dalam satu waktu. Jika login di perangkat baru, sesi perangkat
|           lama otomatis dihapus (paksa logout).
|
| CARA KERJA:
|   1. Saat login berhasil, token unik sesi disimpan di tabel
|      `active_device_sessions` (database) DAN di session browser.
|   2. Setiap request yang masuk, token di session browser dibandingkan
|      dengan token di database.
|   3. Jika tidak cocok → sesi dianggap sudah digantikan perangkat lain
|      → paksa logout → redirect ke halaman login.
|
| CARA PAKAI DI ROUTE (routes/web.php):
|   ->middleware('single.device:admin')   → untuk guard admin
|   ->middleware('single.device:client')  → untuk guard client
|   ->middleware('single.device')         → deteksi guard otomatis
|
| TABEL DATABASE YANG DIGUNAKAN:
|   active_device_sessions (authenticatable_type, authenticatable_id, session_token)
|==========================================================================
*/

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureSingleDeviceSession
{
    /**
     * Periksa apakah sesi perangkat saat ini masih valid.
     *
     * @param Request     $request  Request HTTP yang masuk
     * @param Closure     $next     Lanjutkan jika sesi valid
     * @param string|null $guard    Guard yang dipakai ('admin', 'client', atau null = otomatis)
     */
    public function handle(Request $request, Closure $next, ?string $guard = null): Response
    {
        // ─── Tentukan guard yang aktif ───────────────────────────────────
        // Jika tidak disebutkan, cek URL: /admin/* pakai guard 'admin', selainnya pakai default
        $activeGuard = $guard ?? ($request->is('admin', 'admin/*') ? 'admin' : config('auth.defaults.guard', 'web'));

        // Jika pengguna belum login, biarkan request lanjut (tidak perlu dicek)
        if (!Auth::guard($activeGuard)->check()) {
            return $next($request);
        }

        // ─── Ambil data pengguna & token sesi ───────────────────────────
        $user  = Auth::guard($activeGuard)->user();
        // Token yang disimpan di session browser pengguna saat ini
        $token = $request->session()->get("active_device_token_{$activeGuard}");

        // ─── Ambil sesi aktif dari database ─────────────────────────────
        // Cari record berdasarkan model class dan ID pengguna
        $activeSession = DB::table('active_device_sessions')
            ->where('authenticatable_type', get_class($user))
            ->where('authenticatable_id', $user->getAuthIdentifier())
            ->first();

        // ─── Validasi: bandingkan token browser vs token database ────────
        // Jika salah satu tidak ada, atau token tidak cocok → sesi tidak valid
        if (!$token || !$activeSession || !hash_equals((string) $activeSession->session_token, (string) $token)) {
            // Paksa logout
            Auth::guard($activeGuard)->logout();
            // Hapus token dari session browser
            $request->session()->forget("active_device_token_{$activeGuard}");

            // Arahkan ke halaman login yang sesuai dengan pesan error
            $loginRoute = $activeGuard === 'admin' ? 'admin.login' : 'login';

            return redirect()->route($loginRoute)->withErrors([
                'email' => 'Sesi Anda telah berakhir karena login di perangkat lain. Silakan login kembali.',
            ]);
        }

        // Token valid → lanjutkan request
        return $next($request);
    }
}
