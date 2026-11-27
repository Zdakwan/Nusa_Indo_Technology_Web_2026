<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * ==========================================================================
 * FILE: app/Http/Controllers/Api/MobileLayananSyncController.php
 * ==========================================================================
 * FUNGSI : Menerima permintaan dari aplikasi MOBILE untuk menyinkronkan
 *          data Layanan ke database WEB (mobile → web).
 *
 * ENDPOINT (semua dilindungi middleware mobile.api.key):
 *   GET    /api/internal/layanan           → Daftar semua layanan
 *   POST   /api/internal/layanan           → Tambah layanan baru dari mobile
 *   PUT    /api/internal/layanan/{id_web}  → Perbarui layanan dari mobile
 *   DELETE /api/internal/layanan/{id_web}  → Hapus layanan dari mobile
 *
 * MIDDLEWARE: mobile.api.key (VerifyMobileApiKey)
 *   → Semua request wajib menyertakan header: X-API-KEY: <nilai dari .env MOBILE_API_KEY>
 *
 * CATATAN:
 *   - {id_web} adalah ID layanan di database WEB (bukan ID mobile)
 *   - Penghapusan dari mobile akan menghapus data di web juga
 *   - Semua error dicatat ke laravel.log
 * ==========================================================================
 */
class MobileLayananSyncController extends Controller
{
    /**
     * GET /api/internal/layanan
     * Ambil daftar semua layanan (untuk mobile sync awal / refresh).
     */
    public function index(): JsonResponse
    {
        $layanan = Layanan::latest()->get(['id', 'nama_layanan', 'kategori', 'icon', 'deskripsi']);

        return response()->json([
            'success' => true,
            'total'   => $layanan->count(),
            'data'    => $layanan,
        ]);
    }

    /**
     * POST /api/internal/layanan
     * Tambah layanan baru dari mobile ke database web.
     *
     * Body (JSON):
     *   nama_layanan : string (wajib, maks 255)
     *   kategori     : string (wajib, maks 100)
     *   icon         : string (wajib, maks 50)
     *   deskripsi    : string (wajib)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'kategori'     => 'required|string|max:100',
            'icon'         => 'required|string|max:50',
            'deskripsi'    => 'required|string',
        ]);

        try {
            $layanan = Layanan::create($validated);

            return response()->json([
                'success'  => true,
                'message'  => 'Layanan berhasil ditambahkan ke web.',
                'data'     => [
                    'id_web'       => $layanan->id,
                    'nama_layanan' => $layanan->nama_layanan,
                ],
            ], 201);
        } catch (Throwable $e) {
            Log::error('MobileLayananSync: gagal tambah layanan', [
                'payload' => $request->all(),
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan layanan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PUT /api/internal/layanan/{id_web}
     * Perbarui layanan yang sudah ada berdasarkan ID web.
     *
     * Body (JSON): sama seperti store, semua field opsional (nullable).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $layanan = Layanan::find($id);

        if (! $layanan) {
            return response()->json([
                'success' => false,
                'message' => "Layanan dengan id_web={$id} tidak ditemukan di web.",
            ], 404);
        }

        $validated = $request->validate([
            'nama_layanan' => 'sometimes|string|max:255',
            'kategori'     => 'sometimes|string|max:100',
            'icon'         => 'sometimes|string|max:50',
            'deskripsi'    => 'sometimes|string',
        ]);

        try {
            $layanan->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Layanan berhasil diperbarui di web.',
                'data'    => [
                    'id_web'       => $layanan->id,
                    'nama_layanan' => $layanan->nama_layanan,
                    'updated_at'   => $layanan->updated_at?->toIso8601String(),
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('MobileLayananSync: gagal update layanan', [
                'id_web'  => $id,
                'payload' => $request->all(),
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui layanan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /api/internal/layanan/{id_web}
     * Hapus layanan dari database web (dipanggil saat mobile menghapus layanan).
     *
     * Ini yang menjawab masalah: "di mobile sudah dihapus tapi web masih ada".
     */
    public function destroy(int $id): JsonResponse
    {
        $layanan = Layanan::find($id);

        if (! $layanan) {
            // Layanan tidak ada di web — anggap sudah terhapus (idempotent)
            return response()->json([
                'success' => true,
                'message' => "Layanan id_web={$id} tidak ditemukan (mungkin sudah terhapus).",
            ]);
        }

        try {
            $nama = $layanan->nama_layanan;
            $layanan->delete();

            Log::info('MobileLayananSync: layanan dihapus dari web via mobile', [
                'id_web'       => $id,
                'nama_layanan' => $nama,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Layanan \"{$nama}\" berhasil dihapus dari web.",
            ]);
        } catch (Throwable $e) {
            Log::error('MobileLayananSync: gagal hapus layanan', [
                'id_web' => $id,
                'error'  => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus layanan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
