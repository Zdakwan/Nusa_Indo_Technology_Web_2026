<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Services\MobileApi;
use Illuminate\Console\Command;

class SyncKlienMobile extends Command
{
    protected $signature = 'mobile:sync-klien {--all : Kirim ulang semua klien, bukan hanya yang belum tersinkron}';

    protected $description = 'Kirim data klien web yang belum masuk ke aplikasi mobile';

    public function handle(MobileApi $mobile): int
    {
        if (! $mobile->enabled()) {
            $this->error('Isi MOBILE_API_URL dan MOBILE_API_KEY di .env dulu.');
            return self::FAILURE;
        }

        $query = Client::query();
        if (! $this->option('all')) {
            $query->whereNull('mobile_synced_at');
        }

        $ok = 0;
        $gagal = 0;
        $query->orderBy('id')->each(function (Client $client) use ($mobile, &$ok, &$gagal) {
            if ($mobile->pushClient($client)) {
                $ok++;
            } else {
                $gagal++;
                $this->warn("Gagal: #{$client->id} {$client->name}");
            }
        });

        $this->info("Selesai. Berhasil: {$ok}, gagal: {$gagal}.");
        return $gagal === 0 ? self::SUCCESS : self::FAILURE;
    }
}
