<?php

/*
|==========================================================================
| FILE: app/Http/Controllers/Admin/AdminProyekController.php
|==========================================================================
| FUNGSI  : Mengelola seluruh CRUD Proyek dari sisi Admin.
|           Mencakup:
|             - Lihat daftar proyek (index)
|             - Form tambah proyek (create)
|             - Simpan proyek baru dari konsultasi approved (store)
|             - Lihat detail proyek (show)
|             - Form edit proyek (edit)
|             - Perbarui info proyek (update)
|             - Perbarui progres proyek + notifikasi client (updateProgress)
|             - Hapus proyek (destroy)
|             - Hapus banyak sekaligus (bulkDelete)
|             - Export PDF satu proyek â†’ preview di browser (exportSinglePdf)
|
| ROUTE TERKAIT (routes/web.php):
|   Route::resource('proyek', AdminProyekController::class)
|   â†’ Otomatis membuat: index, create, store, show, edit, update, destroy
|
|   GET  /admin/proyek/{id}/pdf       â†’ exportSinglePdf()
|   POST /admin/proyek/{id}/progress  â†’ updateProgress()
|   DELETE /admin/proyek/bulk-delete  â†’ bulkDelete()
|
| VIEW YANG DIGUNAKAN:
|   resources/views/admin/proyek/index.blade.php
|   resources/views/admin/proyek/create.blade.php
|   resources/views/admin/proyek/show.blade.php
|   resources/views/admin/proyek/edit.blade.php
|   resources/views/admin/proyek/pdf_viewer.blade.php â† halaman preview PDF
|
| TEMPLATE PDF:
|   resources/views/admin/proyek/single_pdf.blade.php â† isi laporan PDF
|
| LIBRARY PDF:
|   barryvdh/laravel-dompdf (dipanggil via Barryvdh\DomPDF\Facade\Pdf)
|==========================================================================
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\RiwayatProyek;
use App\Models\Konsultasi;
use App\Models\Notifikasi;
use App\Models\PreferensiNotifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminProyekController extends Controller
{
    /**
     * Tampilkan daftar seluruh proyek (halaman index).
     * Data diurutkan terbaru, ditampilkan 10 per halaman (pagination).
     */
    public function index()
    {
        $proyek = Proyek::with(['client', 'konsultasi'])
            ->latest()
            ->paginate(10);

        return view('admin.proyek.index', compact('proyek'));
    }

    /**
     * Tampilkan form tambah proyek baru.
     *
     * Hanya konsultasi dengan status 'approved' yang belum punya proyek
     * yang bisa dipilih sebagai dasar pembuatan proyek.
     *
     * Data yang dikirim ke view:
     *   $konsultasis     â†’ koleksi Konsultasi untuk dropdown
     *   $daftarKonsultasi â†’ array JSON untuk JavaScript (pilih konsultasi)
     */
    public function create()
    {
        // Tampilkan konsultasi approved ATAU completed yang belum jadi proyek
        // 'completed' = konsultasi selesai tapi belum dibuatkan proyek pengerjaannya
        $konsultasis = Konsultasi::with('client')
            ->whereIn('status', ['approved', 'completed'])
            ->whereDoesntHave('proyek')
            ->latest()
            ->get();

        // Format data untuk dipakai di JavaScript (dropdown pilih konsultasi)
        $daftarKonsultasi = $konsultasis->map(function ($konsultasi) {
            return [
                'id'         => $konsultasi->id,
                'client'     => $konsultasi->client->name ?? '-',
                'perusahaan' => $konsultasi->nama_perusahaan ?? '-',
                'jenis'      => $konsultasi->jenis_konsultasi ?? 'Umum',
                'tanggal'    => $konsultasi->tanggal_konsultasi
                    ? \Carbon\Carbon::parse($konsultasi->tanggal_konsultasi)
                        ->locale('id')
                        ->translatedFormat('d F Y')
                    : '-',
            ];
        })->values();

        return view('admin.proyek.create', compact(
            'konsultasis',
            'daftarKonsultasi'
        ));
    }

    /**
     * Simpan proyek baru berdasarkan konsultasi yang sudah disetujui.
     *
     * Alur (dalam transaksi database):
     *   1. Ambil & kunci konsultasi yang dipilih
     *   2. Pastikan konsultasi berstatus 'approved'
     *   3. Pastikan konsultasi belum punya proyek
     *   4. Buat proyek baru dengan progres 0
     *   5. Simpan riwayat awal proyek
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'konsultasi_id' => 'required|exists:konsultasi,id',
            'nama_proyek'   => 'required|string|max:255',
            'deskripsi'     => 'nullable|string|max:5000',
        ]);

        // Gunakan transaksi agar tidak terjadi data ganda jika request dikirim 2x
        DB::transaction(function () use ($validated) {

            // Ambil konsultasi dan kunci selama proses penyimpanan (mencegah race condition)
            $konsultasi = Konsultasi::where('id', $validated['konsultasi_id'])
                ->lockForUpdate()
                ->firstOrFail();

            // Validasi: konsultasi harus approved atau completed
            if (! in_array($konsultasi->status, ['approved', 'completed'])) {
                throw ValidationException::withMessages([
                    'konsultasi_id' => 'Konsultasi harus berstatus Disetujui atau Selesai untuk dapat dibuat proyek.',
                ]);
            }

            // Validasi: satu konsultasi hanya bisa jadi satu proyek
            if (Proyek::where('konsultasi_id', $konsultasi->id)->exists()) {
                throw ValidationException::withMessages([
                    'konsultasi_id' => 'Konsultasi ini sudah memiliki proyek.',
                ]);
            }

            $adminId = Auth::guard('admin')->id();

            // Buat proyek baru dengan progres awal 0
            $proyek = Proyek::create([
                'konsultasi_id' => $konsultasi->id,
                'client_id'     => $konsultasi->client_id,
                'admin_id'      => $adminId,
                'nama_proyek'   => $validated['nama_proyek'],
                'deskripsi'     => $validated['deskripsi'] ?? null,
                'progres'       => 0,
                'status'        => 'berjalan',
            ]);

            // Simpan riwayat pertama sebagai catatan pembuatan proyek
            RiwayatProyek::create([
                'proyek_id' => $proyek->id,
                'admin_id'  => $adminId,
                'progres'   => 0,
                'catatan'   => 'Proyek berhasil dibuat berdasarkan konsultasi yang disetujui.',
            ]);
        });

        return redirect()
            ->route('admin.proyek.index')
            ->with('success', 'Proyek berhasil dibuat berdasarkan konsultasi.');
    }

    /**
     * Tampilkan halaman detail proyek beserta riwayat perkembangan.
     * Memuat relasi: client, admin, konsultasi, dan riwayat (dengan admin-nya).
     */
    public function show($id)
    {
        $proyek = Proyek::with([
            'client',
            'admin',
            'konsultasi',
            'riwayat.admin', // riwayat beserta siapa admin yang update
        ])->findOrFail($id);

        return view('admin.proyek.show', compact('proyek'));
    }

    /**
     * Tampilkan halaman edit informasi dasar proyek (nama & deskripsi).
     */
    public function edit($id)
    {
        $proyek = Proyek::with(['client', 'konsultasi'])
            ->findOrFail($id);

        return view('admin.proyek.edit', compact('proyek'));
    }

    /**
     * Perbarui nama dan deskripsi proyek.
     */
    public function update(Request $request, $id)
    {
        $proyek = Proyek::findOrFail($id);

        $validated = $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string|max:5000',
        ]);

        $proyek->update($validated);

        return redirect()
            ->route('admin.proyek.show', $proyek->id)
            ->with('success', 'Informasi proyek berhasil diperbarui.');
    }

    /**
     * Perbarui progres proyek dan kirim notifikasi ke client.
     *
     * Alur (dalam transaksi database):
     *   1. Update kolom progres & status proyek
     *   2. Simpan riwayat perkembangan
     *   3. Cek preferensi notifikasi client
     *   4. Jika preferensi 'updated_proyek' aktif â†’ buat notifikasi
     *
     * Jika progres = 100 â†’ status proyek otomatis berubah menjadi 'selesai'.
     */
    public function updateProgress(Request $request, $id)
    {
        $validated = $request->validate([
            'progres' => 'required|integer|min:0|max:100',
            'catatan' => 'required|string|max:5000',
        ]);

        $adminId = Auth::guard('admin')->id();
        $proyek = Proyek::findOrFail($id);

        DB::transaction(function () use ($proyek, $validated, $adminId) {

            // Update progres; jika 100% â†’ tandai sebagai selesai
            $proyek->update([
                'progres' => $validated['progres'],
                'status'  => $validated['progres'] == 100 ? 'selesai' : 'berjalan',
            ]);

            // Simpan riwayat perkembangan proyek
            RiwayatProyek::create([
                'proyek_id' => $proyek->id,
                'admin_id'  => $adminId,
                'progres'   => $validated['progres'],
                'catatan'   => $validated['catatan'],
            ]);

            // Ambil atau buat preferensi notifikasi client (default semua aktif)
            $preferensi = PreferensiNotifikasi::firstOrCreate(
                ['client_id' => $proyek->client_id],
                [
                    'update_konsultasi'  => true,
                    'perubahan_jadwal'   => true,
                    'updated_proyek'     => true,
                    'artikel_ensiklopedia' => true,
                ]
            );

            // Hanya kirim notifikasi jika preferensi 'updated_proyek' diaktifkan client
            if ($preferensi->updated_proyek) {
                Notifikasi::create([
                    'client_id' => $proyek->client_id,
                    'judul'     => 'Updated Proyek',
                    'pesan'     => 'Progres proyek "' . $proyek->nama_proyek
                        . '" telah diperbarui menjadi '
                        . $validated['progres'] . '%. '
                        . 'Catatan perkembangan: '
                        . $validated['catatan'],
                    'is_read'   => false,
                ]);
            }
        });

        return redirect()
            ->route('admin.proyek.show', $proyek->id)
            ->with('success', 'Progres proyek berhasil diperbarui.');
    }

    /**
     * Hapus satu proyek beserta riwayatnya.
     */
    public function destroy($id)
    {
        $proyek = Proyek::findOrFail($id);
        $proyek->delete();

        return redirect()
            ->route('admin.proyek.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }

    /**
     * Hapus banyak proyek sekaligus (bulk delete).
     * ID proyek dikirim dari form sebagai array.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:' . (new Proyek())->getTable() . ',id',
        ]);

        $count = Proyek::whereIn('id', $request->ids)->count();
        Proyek::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.proyek.index')
            ->with('success', $count . ' proyek berhasil dihapus.');
    }

    /**
     * Generate PDF laporan proyek dan tampilkan di viewer browser.
     *
     * ALUR:
     *   1. Ambil data proyek dari database (dengan relasi client)
     *   2. Render template PDF â†’ resources/views/admin/proyek/single_pdf.blade.php
     *   3. Encode output PDF ke base64
     *   4. Kirim base64 ke halaman viewer â†’ pdf_viewer.blade.php
     *      (Viewer menampilkan PDF di dalam <iframe>, bukan langsung download)
     *   5. User bisa klik tombol download di toolbar viewer
     *
     * MENGAPA PAKAI VIEWER?
     *   Agar browser menampilkan preview terlebih dahulu, tidak langsung
     *   diunduh otomatis oleh Internet Download Manager (IDM) atau browser.
     *
     * UBAH TAMPILAN PDF:
     *   â†’ Edit: resources/views/admin/proyek/single_pdf.blade.php
     *
     * UBAH UKURAN KERTAS / ORIENTASI:
     *   â†’ Edit baris: ->setPaper('a4', 'portrait')
     *   â†’ Pilihan orientasi: 'portrait' atau 'landscape'
     *   â†’ Pilihan kertas: 'a4', 'a3', 'letter', 'legal'
     */
    public function exportSinglePdf($id)
    {
        // 1. Ambil data proyek beserta relasi client
        $proyek = \App\Models\Proyek::with('client')->findOrFail($id);

        // 2. Generate PDF dari template blade
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.proyek.single_pdf', compact('proyek'))
                ->setPaper('a4', 'portrait')
                ->setOption('isRemoteEnabled', true); // izinkan gambar dari URL eksternal

        // 3. Encode PDF ke base64 agar bisa dikirim ke view sebagai string
        $pdfBase64 = base64_encode($pdf->output());

        // 4. Nama file yang akan digunakan saat user klik download
        $filename = 'Laporan-Rincian-' . str_replace(' ', '-', $proyek->nama_proyek) . '.pdf';

        // 5. Tampilkan halaman viewer (bukan download langsung)
        return view('admin.proyek.pdf_viewer', compact('pdfBase64', 'filename', 'proyek'));
    }
}
