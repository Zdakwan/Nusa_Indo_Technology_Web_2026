<?php

/*
|==========================================================================
| FILE: routes/api.php
|==========================================================================
| Semua route di sini diawali dengan /api/ secara otomatis oleh Laravel.
|
| MIDDLEWARE GLOBAL (semua route di bawah prefix 'internal'):
|   mobile.api.key  → wajib sertakan header X-API-KEY: <MOBILE_API_KEY di .env>
|   throttle:60,1   → maks 60 request per menit per IP
|
| ══════════════════════════════════════════════════════════════════════════
| DAFTAR ENDPOINT LENGKAP (BASE URL: /api/internal)
| ══════════════════════════════════════════════════════════════════════════
|
| ── PROYEK (Mobile ↔ Web) ─────────────────────────────────────────────────
|   GET    /proyek                      → Ambil daftar proyek (dengan filter status)
|   GET    /proyek/{id}                 → Detail satu proyek beserta riwayat
|   PATCH  /proyek/{id}/progress        → ★ Update progres & catatan dari mobile
|                                           Body: { progres, catatan, nama_karyawan? }
|                                           Efek: update DB web + notif client + riwayat
|
| ── LAYANAN (Mobile → Web) ────────────────────────────────────────────────
|   GET    /layanan                     → Ambil daftar semua layanan dari web
|   POST   /layanan                     → Tambah layanan baru dari mobile ke web
|   PUT    /layanan/{id_web}            → Perbarui layanan berdasarkan ID web
|   DELETE /layanan/{id_web}            → ★ Hapus layanan dari web (sinkronkan penghapusan mobile)
|
| ══════════════════════════════════════════════════════════════════════════
| CARA MENAMBAH ENDPOINT BARU:
|   1. Buat Controller baru di app/Http/Controllers/Api/
|   2. use di atas
|   3. Tambah Route di dalam group di bawah
| ══════════════════════════════════════════════════════════════════════════
*/

use App\Http\Controllers\Api\MobileLayananSyncController;
use App\Http\Controllers\Api\MobileProyekSyncController;
use Illuminate\Support\Facades\Route;

Route::prefix('internal')
    ->middleware(['mobile.api.key', 'throttle:60,1'])
    ->group(function () {

        // ──────────────────────────────────────────────────────────────────
        // PROYEK — Sinkronisasi dua arah (web ↔ mobile)
        // Mobile membaca data proyek dari web, lalu kirim update progres balik ke web.
        // ──────────────────────────────────────────────────────────────────

        // Ambil daftar proyek (opsional filter: ?status=berjalan|selesai&per_page=20)
        Route::get('/proyek', [MobileProyekSyncController::class, 'index']);

        // Ambil detail satu proyek beserta riwayat progres
        Route::get('/proyek/{id}', [MobileProyekSyncController::class, 'show'])
            ->whereNumber('id');

        // ★ Update progres proyek dari aplikasi mobile → langsung update DB web
        //   Body JSON: { "progres": 75, "catatan": "...", "nama_karyawan": "..." }
        //   Dampak   : update tabel proyek + buat riwayat + kirim notifikasi ke client
        Route::match(['patch', 'put'], '/proyek/{id}/progress', [MobileProyekSyncController::class, 'updateProgress'])
            ->whereNumber('id');

        // ──────────────────────────────────────────────────────────────────
        // LAYANAN — Sinkronisasi mobile → web
        // Sebelumnya hanya web → mobile (via MobileApi::pushLayanan).
        // Sekarang mobile juga bisa push perubahan ke web.
        // ──────────────────────────────────────────────────────────────────

        // Ambil daftar layanan dari web (untuk seed awal di mobile)
        Route::get('/layanan', [MobileLayananSyncController::class, 'index']);

        // Tambah layanan baru dari mobile → tersimpan di DB web
        Route::post('/layanan', [MobileLayananSyncController::class, 'store']);

        // Perbarui layanan berdasarkan id_web
        Route::put('/layanan/{id}', [MobileLayananSyncController::class, 'update'])
            ->whereNumber('id');

        // ★ Hapus layanan dari web saat mobile menghapusnya
        //   Ini jawaban dari masalah: "di mobile sudah dihapus tapi web masih ada"
        Route::delete('/layanan/{id}', [MobileLayananSyncController::class, 'destroy'])
            ->whereNumber('id');
    });
