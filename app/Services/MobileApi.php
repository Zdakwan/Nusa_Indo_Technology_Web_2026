<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Layanan;
use App\Models\Proyek; 
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Jembatan web -> API mobile (nusa-api/api/klien_masuk.php).
 * Database web dan mobile tetap terpisah; data klien dikirim lewat HTTP.
 * Kegagalan di sini TIDAK boleh menggagalkan proses register/ubah profil.
 */
class MobileApi
{
    public function enabled(): bool
    {
        return filled(config('services.mobile_api.url'))
            && filled(config('services.mobile_api.key'));
    }

    /** Kirim (atau perbarui) satu klien ke aplikasi mobile. true = berhasil. */
    public function pushClient(Client $client): bool
    {
        if (! $this->enabled()) {
            Log::warning('MOBILE_API_URL / MOBILE_API_KEY belum diisi di .env; klien tidak disinkronkan.');
            return false;
        }

        // Batas panjang mengikuti kolom di database mobile (klien.nama_klien & klien.email = 100).
        $payload = [
            'id_web' => (string) $client->id,
            'nama'   => mb_substr((string) $client->name, 0, 100),
            'kontak' => mb_substr((string) $client->phone, 0, 30),
            'email'  => mb_substr((string) $client->email, 0, 100),
            'alamat' => (string) ($client->address ?? ''),
        ];

        try {
            $res = Http::withHeaders(['X-API-KEY' => config('services.mobile_api.key')])
                ->acceptJson()
                ->asJson()
                ->connectTimeout(3)
                ->timeout(5)
                ->post(rtrim(config('services.mobile_api.url'), '/') . '/klien_masuk.php', $payload);

            if ($res->successful() && $res->json('success') === true) {
                $client->forceFill(['mobile_synced_at' => now()])->saveQuietly();
                return true;
            }

            Log::warning('API mobile menolak data klien', [
                'client_id' => $client->id,
                'status'    => $res->status(),
                'body'      => $res->body(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Tidak bisa menghubungi API mobile: ' . $e->getMessage(), ['client_id' => $client->id]);
        }

        return false;
    }

    /** Kirim (atau perbarui) satu layanan ke aplikasi mobile. true = berhasil. */
    public function pushLayanan(Layanan $layanan): bool
    {
        // Batas panjang mengikuti kolom mobile (layanan.nama_layanan = 150). Kategori & icon tidak dipakai di app.
        return $this->kirim('layanan_masuk.php', [
            'aksi'      => 'simpan',
            'id_web'    => (string) $layanan->id,
            'nama'      => mb_substr((string) $layanan->nama_layanan, 0, 150),
            'deskripsi' => (string) $layanan->deskripsi,
        ]);
    }

    /**
     * Kirim (atau perbarui) satu proyek beserta data kliennya ke aplikasi mobile
     * agar supervisor bisa memutuskan. Aman dipanggil berulang: mobile memakai id_web
     * sebagai kunci unik dan tidak menimpa keputusan supervisor.
     */
    public function pushProyek(Proyek $proyek): bool
    {
        $proyek->loadMissing('client');
        $c = $proyek->client;

        if (! $c) {
            return false;
        }

        return $this->kirim('proyek_masuk.php', [
            'id_web'      => (string) $proyek->id,
            'nama_proyek' => mb_substr((string) $proyek->nama_proyek, 0, 255),
            'deskripsi'   => (string) $proyek->deskripsi,
            'status_web'  => (string) $proyek->status,
            'progres'     => (int) $proyek->progres,
            'client'      => [
                'id_web' => (string) $c->id,
                'nama'   => mb_substr((string) $c->name, 0, 100),
                'kontak' => mb_substr((string) $c->phone, 0, 30),
                'email'  => mb_substr((string) $c->email, 0, 100),
                'alamat' => (string) ($c->address ?? ''),
            ],
        ]);
    }

    /** Hapus layanan di aplikasi mobile saat layanan di web dihapus. */
    public function deleteLayanan(int|string $idWeb): bool
    {
        return $this->kirim('layanan_masuk.php', ['aksi' => 'hapus', 'id_web' => (string) $idWeb]);
    }

    private function kirim(string $endpoint, array $payload): bool
    {
        if (! $this->enabled()) {
            Log::warning('MOBILE_API_URL / MOBILE_API_KEY belum diisi di .env; data tidak disinkronkan.');
            return false;
        }

        try {
            $res = Http::withHeaders(['X-API-KEY' => config('services.mobile_api.key')])
                ->acceptJson()
                ->asJson()
                ->connectTimeout(3)
                ->timeout(5)
                ->post(rtrim(config('services.mobile_api.url'), '/') . '/' . $endpoint, $payload);

            if ($res->successful() && $res->json('success') === true) {
                return true;
            }

            Log::warning('API mobile menolak data', [
                'endpoint' => $endpoint,
                'payload'  => $payload,
                'status'   => $res->status(),
                'body'     => $res->body(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Tidak bisa menghubungi API mobile: ' . $e->getMessage(), ['endpoint' => $endpoint]);
        }

        return false;
    }
}
