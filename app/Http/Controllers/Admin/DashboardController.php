<?php

/*
|==========================================================================
| FILE: app/Http/Controllers/Admin/DashboardController.php
|==========================================================================
| FUNGSI  : Mengelola seluruh logika halaman Dashboard Admin.
|           Mencakup:
|             - Menampilkan data statistik konsultasi (index)
|             - Tambah aktivitas konsultasi manual (storeAktivitas)
|             - Edit aktivitas (updateAktivitas)
|             - Hapus aktivitas satu per satu (destroyAktivitas)
|             - Hapus banyak sekaligus (bulkDestroyAktivitas)
|             - Export laporan ke Excel (exportExcel)
|
| VIEW YANG DIGUNAKAN:
|   resources/views/admin/dashboard.blade.php
|   resources/views/admin/partials/dashboard-charts.blade.php (di-include)
|
| EXPORT EXCEL:
|   app/Exports/KonsultasiExport.php  → class yang mendefinisikan isi file xlsx
|
| ROUTE TERKAIT (routes/web.php):
|   GET  /admin/dashboard              → index()
|   GET  /admin/dashboard/export-excel → exportExcel()
|   POST /admin/dashboard/aktivitas    → storeAktivitas()
|   PUT  /admin/dashboard/aktivitas/{id} → updateAktivitas()
|   DELETE /admin/dashboard/aktivitas/{id} → destroyAktivitas()
|   DELETE /admin/dashboard/aktivitas  → bulkDestroyAktivitas()
|==========================================================================
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Exports\KonsultasiExport;
use App\Models\Konsultasi;
use App\Models\Client;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama Dashboard Admin.
     *
     * Data yang dikirim ke view:
     *   $aktivitas       → semua data konsultasi (untuk tabel aktivitas)
     *   $konsultasi      → alias $aktivitas (untuk chart)
     *   $totalPermintaan → jumlah seluruh konsultasi
     *   $menunggu        → jumlah konsultasi berstatus pending
     *   $diproses        → jumlah konsultasi berstatus approved
     *   $selesai         → jumlah konsultasi berstatus completed
     *   $clients         → daftar client untuk dropdown form tambah aktivitas
     */
    public function index()
    {
        // Ambil semua konsultasi beserta data client-nya, urutkan terbaru
        $aktivitas = Konsultasi::with('client')
            ->latest()
            ->get();

        $konsultasi = $aktivitas; // Alias untuk digunakan di chart

        // Hitung statistik berdasarkan status
        $totalPermintaan = $aktivitas->count();
        $menunggu = $aktivitas->whereIn('status', ['pending', 'Menunggu'])->count();
        $diproses = $aktivitas->whereIn('status', ['approved', 'Diproses'])->count();
        $selesai  = $aktivitas->whereIn('status', ['completed', 'Selesai'])->count();

        // Daftar client untuk dropdown di modal tambah aktivitas
        $clients = Client::orderBy('name')->get();

        return view('admin.dashboard', compact(
            'aktivitas',
            'konsultasi',
            'totalPermintaan',
            'menunggu',
            'diproses',
            'selesai',
            'clients'
        ));
    }

    /**
     * Redirect ke dashboard (tidak dipakai langsung, hanya fallback).
     */
    public function createAktivitas()
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Simpan aktivitas konsultasi baru yang diinput manual oleh admin.
     *
     * Alur:
     *   1. Validasi input form
     *   2. Cari client di database (by client_id atau nama/perusahaan)
     *   3. Jika client tidak ditemukan → buat client dummy baru
     *   4. Simpan konsultasi baru
     *   5. Kirim notifikasi ke client
     */
    public function storeAktivitas(Request $request)
    {
        // Validasi input wajib dari form
        $request->validate([
            'nama_klien'        => 'required|string|max:255',
            'permintaan'        => 'required|string',
            'tanggal_pengajuan' => 'required|date',
            'status'            => 'required|string',
        ]);

        // ─── Cari client yang sesuai ─────────────────────────────────────
        $client = null;

        // Prioritas 1: Cari lewat client_id jika dipilih dari dropdown
        if ($request->filled('client_id')) {
            $client = Client::find($request->client_id);
        }

        // Prioritas 2: Cari berdasarkan nama perusahaan atau nama client
        if (!$client) {
            $client = Client::where('company', $request->nama_klien)
                ->orWhere('name', $request->nama_klien)
                ->first();
        }

        // Prioritas 3: Buat client baru jika tidak ditemukan sama sekali
        if (!$client) {
            $clientColumns = Schema::getColumnListing('clients');
            $newClientData = [
                'name'       => $request->nama_klien,
                // Username dibuat dari slug nama + angka acak agar unik
                'username'   => Str::slug($request->nama_klien, '_') . '_' . rand(100, 999),
                // Email dummy, bisa diperbarui nanti
                'email'      => Str::slug($request->nama_klien, '') . rand(10, 99) . '@klien.com',
                'password'   => Hash::make('Password123!'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Cek kolom opsional sebelum dimasukkan (agar tidak error jika kolom tidak ada)
            if (in_array('no_telp', $clientColumns)) {
                $newClientData['no_telp'] = '081234567890';
            }
            if (in_array('company', $clientColumns)) {
                $newClientData['company'] = $request->nama_klien;
            }

            $clientId = DB::table('clients')->insertGetId($newClientData);
        } else {
            $clientId = $client->id;
            // Update nama perusahaan jika kolom ada
            if (Schema::hasColumn('clients', 'company')) {
                DB::table('clients')->where('id', $clientId)->update([
                    'company' => $request->nama_klien
                ]);
            }
        }

        // ─── Simpan data konsultasi baru ─────────────────────────────────
        // Cek kolom yang ada di tabel (agar fleksibel terhadap versi skema)
        $columns = Schema::getColumnListing('konsultasi');
        $data = [
            'client_id'  => $clientId,
            'status'     => $request->status,
            'created_at' => $request->tanggal_pengajuan . ' ' . now()->format('H:i:s'),
            'updated_at' => now(),
        ];

        // Isi kolom yang relevan sesuai skema tabel yang ada
        if (in_array('catatan_klien', $columns)) $data['catatan_klien'] = $request->permintaan;
        if (in_array('topik', $columns)) $data['topik'] = $request->permintaan;
        if (in_array('permintaan', $columns)) $data['permintaan'] = $request->permintaan;
        if (in_array('jenis_layanan', $columns)) $data['jenis_layanan'] = $request->permintaan;
        if (in_array('tanggal_konsultasi', $columns)) $data['tanggal_konsultasi'] = $request->tanggal_pengajuan;
        if (in_array('waktu_konsultasi', $columns)) $data['waktu_konsultasi'] = '09:00:00';

        $konsultasiId = DB::table('konsultasi')->insertGetId($data);

        // ─── Kirim notifikasi ke client ───────────────────────────────────
        if (Schema::hasTable('notifikasi')) {
            DB::table('notifikasi')->insert([
                'client_id'  => $clientId,
                'judul'      => 'Jadwal & Aktivitas Konsultasi #' . $konsultasiId,
                'pesan'      => 'Admin telah menambahkan aktivitas konsultasi: ' . $request->permintaan,
                'is_read'    => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Aktivitas berhasil ditambahkan!');
    }

    /**
     * Perbarui data aktivitas konsultasi yang sudah ada.
     * Juga mengirim notifikasi perubahan status ke client.
     */
    public function updateAktivitas(Request $request, int $id)
    {
        $request->validate([
            'nama_klien'        => 'required|string|max:255',
            'permintaan'        => 'required|string',
            'tanggal_pengajuan' => 'required|date',
            'status'            => 'required|string',
        ]);

        $konsultasi = Konsultasi::findOrFail($id);

        // ─── Update nama client ───────────────────────────────────────────
        if ($konsultasi->client_id) {
            $updateClient = ['name' => $request->nama_klien];
            if (Schema::hasColumn('clients', 'company')) {
                $updateClient['company'] = $request->nama_klien;
            }
            DB::table('clients')->where('id', $konsultasi->client_id)->update($updateClient);
        }

        // ─── Update data konsultasi ───────────────────────────────────────
        $columns = Schema::getColumnListing('konsultasi');
        $data = [
            'status'     => $request->status,
            'updated_at' => now(),
        ];

        if (in_array('catatan_klien', $columns)) $data['catatan_klien'] = $request->permintaan;
        if (in_array('tanggal_konsultasi', $columns)) $data['tanggal_konsultasi'] = $request->tanggal_pengajuan;

        DB::table('konsultasi')->where('id', $id)->update($data);

        // ─── Kirim notifikasi perubahan status ke client ──────────────────
        if ($konsultasi->client_id && Schema::hasTable('notifikasi')) {
            // Terjemahkan kode status ke label yang mudah dibaca
            $labelStatus = match ($request->status) {
                'approved', 'Diproses'  => 'Disetujui / Diproses',
                'completed', 'Selesai'  => 'Selesai',
                'rejected', 'Ditolak'   => 'Ditolak',
                default                 => 'Menunggu',
            };

            DB::table('notifikasi')->insert([
                'client_id'  => $konsultasi->client_id,
                'judul'      => 'Status Konsultasi #' . $id . ' ' . $labelStatus,
                'pesan'      => 'Permintaan "' . $request->permintaan . '" kini berstatus: ' . $labelStatus . '.',
                'is_read'    => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Aktivitas berhasil diperbarui!');
    }

    /**
     * Hapus satu aktivitas konsultasi berdasarkan ID.
     */
    public function destroyAktivitas(int $id)
    {
        DB::table('konsultasi')->where('id', $id)->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Aktivitas berhasil dihapus!');
    }

    /**
     * Hapus banyak aktivitas konsultasi sekaligus (bulk delete).
     * Daftar ID dikirim melalui form sebagai array.
     */
    public function bulkDestroyAktivitas(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:konsultasi,id',
        ]);

        $count = count($request->ids);
        DB::table('konsultasi')->whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', $count . ' aktivitas berhasil dihapus!');
    }

    /**
     * Export seluruh data konsultasi ke file Excel (.xlsx).
     *
     * File Excel yang dihasilkan memiliki 2 sheet:
     *   - Sheet 1 (KonsultasiGrafikSheet): Data angka untuk grafik/ringkasan
     *   - Sheet 2 (KonsultasiDetailSheet): Tabel detail semua konsultasi
     *
     * Nama file mengandung tanggal hari ini untuk memudahkan identifikasi.
     * Contoh: laporan-permintaan-konsultasi-2026-11-26.xlsx
     *
     * Tombol export ada di: resources/views/admin/partials/dashboard-charts.blade.php
     */
    public function exportExcel()
    {
        // Nama file dinamis menyertakan tanggal hari ini
        $fileName = 'laporan-permintaan-konsultasi-' . now()->format('Y-m-d') . '.xlsx';

        // Excel::download() memproses KonsultasiExport (2 sheet) lalu langsung
        // mengirim file ke browser sebagai unduhan
        return Excel::download(new KonsultasiExport(), $fileName);
    }
}
