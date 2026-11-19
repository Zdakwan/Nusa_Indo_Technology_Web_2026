<?php

namespace App\Console\Commands;

use App\Models\Proyek;
use App\Services\MobileApi;
use Illuminate\Console\Command;

class SyncProyekMobile extends Command
{
    protected $signature = 'mobile:sync-proyek {--semua : Kirim semua proyek, bukan hanya yang menunggu supervisor}';

    protected $description = 'Kirim proyek web ke aplikasi mobile (default: yang masih menunggu keputusan supervisor)';

    public function handle(MobileApi $mobile): int
    {
        if (! $mobile->enabled()) {
            $this->error('Isi MOBILE_API_URL dan MOBILE_API_KEY di .env dulu.');
            return self::FAILURE;
        }

        $query = Proyek::with('client');
        if (! $this->option('semua')) {
            $query->where('status', 'menunggu_supervisor');
        }

        $ok = 0;
        $gagal = 0;
        $query->orderBy('id')->each(function (Proyek $proyek) use ($mobile, &$ok, &$gagal) {
            if ($mobile->pushProyek($proyek)) {
                $ok++;
            } else {
                $gagal++;
                $this->warn("Gagal: #{$proyek->id} {$proyek->nama_proyek}");
            }
        });

        $this->info("Selesai. Berhasil: {$ok}, gagal: {$gagal}.");
        return $gagal === 0 ? self::SUCCESS : self::FAILURE;
    }
}