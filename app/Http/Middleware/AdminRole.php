<?php

/*
|==========================================================================
| FILE: app/Http/Middleware/AdminRole.php
|==========================================================================
| FUNGSI  : Memastikan admin yang sedang login memiliki role yang diizinkan
|           untuk mengakses route tertentu.
|
| CARA PAKAI DI ROUTE (routes/web.php):
|   ->middleware('admin.role:super_admin')
|   ->middleware('admin.role:super_admin,admin_layanan')
|   ->middleware('admin.role:super_admin,admin_konten,admin_layanan')
|
| ROLE YANG ADA DI SISTEM:
|   - super_admin     → akses penuh ke seluruh fitur admin
|   - admin_konten   → kelola konten (portofolio, ensiklopedia, pelatihan)
|   - admin_layanan  → kelola layanan, approval, proyek
|
| JIKA AKSES DITOLAK:
|   Akan muncul halaman error 403 (Forbidden).
|==========================================================================
*/

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRole
{
    /**
     * Periksa apakah admin yang login memiliki salah satu role yang diizinkan.
     *
     * @param Request  $request  Request HTTP yang masuk
     * @param Closure  $next     Lanjutkan ke handler berikutnya jika lolos
     * @param string[] ...$roles Daftar role yang diizinkan (dari parameter route)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Ambil data admin yang sedang login menggunakan guard 'admin'
        $admin = Auth::guard('admin')->user();

        // Jika tidak ada admin yang login, atau role-nya tidak ada dalam daftar,
        // tolak akses dengan HTTP 403
        if (! $admin || ! in_array($admin->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Role cocok → lanjutkan request ke controller
        return $next($request);
    }
}
