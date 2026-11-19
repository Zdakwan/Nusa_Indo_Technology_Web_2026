<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\PreferensiNotifikasi;
use App\Models\Proyek;
use App\Models\RiwayatProyek;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class MobileProyekSyncController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status'   => ['nullable', 'in:menunggu_supervisor,ditolak,berjalan,selesai'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = Proyek::with(['client:id,name,email,company', 'konsultasi:id,jenis_konsultasi'])
            ->latest();

        if (! empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        $proyek = $query->paginate($validated['per_page'] ?? 20);

        return response()->json([
            'success' => true,
            'data'    => $proyek->items(),
            'meta'    => [
                'current_page' => $proyek->currentPage(),
                'last_page'    => $proyek->lastPage(),
                'total'        => $proyek->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $proyek = Proyek::with(['client:id,name,email,company', 'riwayat'])
            ->find($id);

        if (! $proyek) {
            return response()->json([
                'success' => false,
                'message' => 'Proyek tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $proyek,
        ]);
    }

    public function updateProgress(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'progres'       => ['required', 'integer', 'min:0', 'max:100'],
            'catatan'       => ['required', 'string', 'max:5000'],
            'nama_karyawan' => ['nullable', 'string', 'max:100'],
            // true = pengiriman ulang dari mobile; jangan buat riwayat/notifikasi ganda bila progres sudah sama
            'sinkron_ulang' => ['nullable', 'boolean'],
        ], [
            'progres.required' => 'Nilai progres wajib diisi.',
            'progres.integer'  => 'Nilai progres harus berupa angka bulat.',
            'progres.min'      => 'Nilai progres minimal 0.',
            'progres.max'      => 'Nilai progres maksimal 100.',
            'catatan.required' => 'Deskripsi perkembangan tugas wajib diisi.',
        ]);

        try {
            $proyek = DB::transaction(function () use ($id, $validated) {
                // Kunci baris proyek untuk mencegah race condition saat update bersamaan
                $proyek = Proyek::whereKey($id)->lockForUpdate()->first();

                if (! $proyek) {
                    return null;
                }

                // Proyek yang belum disetujui supervisor / ditolak tidak boleh menerima progres.
                if (in_array($proyek->status, ['menunggu_supervisor', 'ditolak'], true)) {
                    throw new \DomainException('Proyek belum disetujui supervisor.');
                }

                $nilaiProgres = (int) $validated['progres'];
                $statusBaru   = $nilaiProgres === 100 ? 'selesai' : 'berjalan';

                if (! empty($validated['sinkron_ulang'])
                    && (int) $proyek->progres === $nilaiProgres
                    && $proyek->status === $statusBaru) {
                    return $proyek;
                }

                $proyek->update([
                    'progres' => $nilaiProgres,
                    'status'  => $statusBaru,
                ]);

                $prefixAktor = ! empty($validated['nama_karyawan'])
                    ? '[Mobile - ' . trim($validated['nama_karyawan']) . '] '
                    : '[Mobile] ';

                RiwayatProyek::create([
                    'proyek_id' => $proyek->id,
                    'admin_id'  => null,
                    'progres'   => $nilaiProgres,
                    'catatan'   => $prefixAktor . trim($validated['catatan']),
                ]);

                if ($proyek->client_id) {
                    $preferensi = PreferensiNotifikasi::firstOrCreate(
                        ['client_id' => $proyek->client_id],
                        [
                            'update_konsultasi'    => true,
                            'perubahan_jadwal'     => true,
                            'updated_proyek'       => true,
                            'artikel_ensiklopedia' => true,
                        ]
                    );

                    if ($preferensi->updated_proyek) {
                        Notifikasi::create([
                            'client_id' => $proyek->client_id,
                            'judul'     => 'Updated Proyek',
                            'pesan'     => 'Progres proyek "' . $proyek->nama_proyek
                                . '" telah diperbarui menjadi ' . $nilaiProgres . '%. '
                                . 'Catatan perkembangan: ' . trim($validated['catatan']),
                            'is_read'   => false,
                        ]);
                    }
                }

                return $proyek->fresh();
            });

            if (! $proyek) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyek tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Progres proyek berhasil disinkronkan ke Web.',
                'data'    => [
                    'web_proyek_id' => $proyek->id,
                    'nama_proyek'   => $proyek->nama_proyek,
                    'progres'       => $proyek->progres,
                    'status'        => $proyek->status,
                    'updated_at'    => $proyek->updated_at?->toIso8601String(),
                ],
            ]);
        } catch (\DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            Log::error('Gagal sinkronisasi progres proyek dari mobile', [
                'proyek_id' => $id,
                'error'     => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memperbarui progres proyek.',
            ], 500);
        }
    }
    
    /**
     * Keputusan supervisor (dari aplikasi mobile) atas proyek client: disetujui | ditolak | menunggu.
     * - disetujui -> status web "berjalan"
     * - ditolak   -> status web "ditolak" + client mendapat notifikasi
     * - menunggu  -> tetap "menunggu_supervisor" (hanya catatan yang diperbarui)
     * Aman dikirim ulang: isi yang sama tidak membuat riwayat/notifikasi ganda.
     */
    public function keputusan(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status'     => ['required', 'in:disetujui,ditolak,menunggu'],
            'catatan'    => ['nullable', 'string', 'max:5000'],
            'supervisor' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $hasil = DB::transaction(function () use ($id, $validated) {
                $proyek = Proyek::whereKey($id)->lockForUpdate()->first();

                if (! $proyek) {
                    return null;
                }

                if ($proyek->status === 'selesai') {
                    throw new \DomainException('Proyek sudah selesai.');
                }

                $statusBaru = match ($validated['status']) {
                    'disetujui' => 'berjalan',
                    'ditolak'   => 'ditolak',
                    default     => 'menunggu_supervisor',
                };
                $catatan = isset($validated['catatan']) ? trim($validated['catatan']) : null;
                $oleh    = ! empty($validated['supervisor']) ? trim($validated['supervisor']) : 'Supervisor';

                if ($proyek->status === $statusBaru && $proyek->catatan_supervisor === $catatan) {
                    return ['proyek' => $proyek, 'berubah' => false];
                }

                $proyek->update([
                    'status'             => $statusBaru,
                    'catatan_supervisor' => $catatan,
                    'keputusan_at'       => now(),
                ]);

                $label = ['disetujui' => 'disetujui', 'ditolak' => 'ditolak', 'menunggu' => 'ditunda (menunggu)'][$validated['status']];

                RiwayatProyek::create([
                    'proyek_id' => $proyek->id,
                    'admin_id'  => null,
                    'progres'   => $proyek->progres,
                    'catatan'   => '[Mobile - ' . $oleh . '] Proyek ' . $label . '.' . ($catatan ? ' Catatan: ' . $catatan : ''),
                ]);

                $this->notifikasiKeputusan($proyek, $validated['status'], $catatan);

                return ['proyek' => $proyek->fresh(), 'berubah' => true];
            });

            if (! $hasil) {
                return response()->json(['success' => false, 'message' => 'Proyek tidak ditemukan.'], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Keputusan supervisor berhasil disinkronkan ke Web.',
                'data'    => [
                    'web_proyek_id' => $hasil['proyek']->id,
                    'status'        => $hasil['proyek']->status,
                    'berubah'       => $hasil['berubah'],
                ],
            ]);
        } catch (\DomainException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 409);
        } catch (Throwable $e) {
            Log::error('Gagal sinkronisasi keputusan supervisor dari mobile', [
                'proyek_id' => $id,
                'error'     => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan keputusan.',
            ], 500);
        }
    }

    private function notifikasiKeputusan(Proyek $proyek, string $keputusan, ?string $catatan): void
    {
        if (! $proyek->client_id || $keputusan === 'menunggu') {
            return;
        }

        if ($keputusan === 'ditolak') {
            Notifikasi::create([
                'client_id' => $proyek->client_id,
                'judul'     => 'Proyek Ditolak',
                'pesan'     => 'Mohon maaf, proyek "' . $proyek->nama_proyek . '" belum dapat kami kerjakan.'
                    . ($catatan ? ' Alasan: ' . $catatan : ''),
                'is_read'   => false,
            ]);

            return;
        }

        $preferensi = PreferensiNotifikasi::firstOrCreate(
            ['client_id' => $proyek->client_id],
            [
                'update_konsultasi'    => true,
                'perubahan_jadwal'     => true,
                'updated_proyek'       => true,
                'artikel_ensiklopedia' => true,
            ]
        );

        if ($preferensi->updated_proyek) {
            Notifikasi::create([
                'client_id' => $proyek->client_id,
                'judul'     => 'Proyek Disetujui',
                'pesan'     => 'Proyek "' . $proyek->nama_proyek . '" telah disetujui dan akan segera dikerjakan tim kami.',
                'is_read'   => false,
            ]);
        }
    }
}