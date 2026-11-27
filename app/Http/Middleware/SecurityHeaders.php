<?php

/*
|==========================================================================
| FILE: app/Http/Middleware/SecurityHeaders.php
|==========================================================================
| FUNGSI  : Menambahkan header keamanan HTTP ke setiap response aplikasi.
|           Header ini melindungi dari serangan umum di web:
|
|   X-Frame-Options       → Mencegah halaman dibuka dalam iframe (clickjacking)
|   X-Content-Type-Options → Mencegah browser menebak tipe file (MIME sniffing)
|   Referrer-Policy        → Batasi info referrer yang dikirim ke server lain
|   Permissions-Policy     → Blokir akses kamera/mic/GPS tanpa izin eksplisit
|   Strict-Transport-Security → Paksa HTTPS (hanya aktif di koneksi HTTPS)
|   X-Powered-By           → Dihapus agar tidak bocor info teknologi server
|
| MIDDLEWARE INI DIDAFTARKAN DI: bootstrap/app.php → $middleware->append(...)
| Artinya berjalan otomatis di SETIAP request, tidak perlu dipasang per route.
|==========================================================================
*/

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Tambahkan header keamanan ke response sebelum dikembalikan ke browser.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jalankan request ke handler berikutnya, lalu ambil response-nya
        $response = $next($request);

        // ─── Header Keamanan Umum ────────────────────────────────────────
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        // → Hanya izinkan halaman dimuat dalam iframe dari domain yang sama

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        // → Paksa browser pakai Content-Type yang dideklarasikan, jangan ditebak

        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        // → Kirim URL lengkap hanya ke domain sama; kirim origin saja ke domain lain

        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        // → Blokir total akses ke kamera, mikrofon, dan GPS

        // ─── HSTS (HTTP Strict Transport Security) ───────────────────────
        // Hanya aktifkan di HTTPS (mis. di Vercel Production), bukan localhost
        // Fungsi: Paksa browser selalu pakai HTTPS selama 1 tahun ke depan
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // ─── Hapus Header X-Powered-By ───────────────────────────────────
        // Mencegah bocornya info "Laravel" / "PHP" ke penyerang
        $response->headers->remove('X-Powered-By');

        // ─── Nonaktifkan Cache untuk Halaman Admin & Auth ────────────────
        // Mencegah browser menyimpan halaman admin di cache (tekan tombol Back
        // setelah logout seharusnya tidak menampilkan halaman admin lagi)
        if ($request->is('admin', 'admin/*', 'login', 'logout')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }

        return $response;
    }
}
