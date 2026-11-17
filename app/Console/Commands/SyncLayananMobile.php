<?php

namespace App\Console\Commands;

use App\Models\Layanan;
use App\Services\MobileApi;
use Illuminate\Console\Command;

class SyncLayananMobile extends Command
{
    protected $signature = 'mobile:sync-layanan';

    protected $description = 'Kirim semua layanan web ke aplikasi mobile (aman diulang: data diperbarui, tidak dobel)';

    public function handle(MobileApi $mobile): int
    {
        if (! $mobile->enabled()) {
            $this->error('Isi MOBILE_API_URL dan MOBILE_API_KEY di .env dulu.');
            return self::FAILURE;
        }

        $ok = 0;
        $gagal = 0;
        Layanan::orderBy('id')->each(function (Layanan $layanan) use ($mobile, &$ok, &$gagal) {
            if ($mobile->pushLayanan($layanan)) {
                $ok++;
            } else {
                $gagal++;
                $this->warn("Gagal: #{$layanan->id} {$layanan->nama_layanan}");
            }
        });

        $this->info("Selesai. Berhasil: {$ok}, gagal: {$gagal}.");
        return $gagal === 0 ? self::SUCCESS : self::FAILURE;
    }
}
