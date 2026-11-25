<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['anggota_tims', 'anggota_tim'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'foto')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `foto` LONGTEXT NULL");
            }
        }

        foreach (['tentang_kamis', 'tentang_kami'] as $table) {
            if (Schema::hasTable($table)) {
                if (Schema::hasColumn($table, 'foto_kolaborasi_tim')) {
                    DB::statement("ALTER TABLE `{$table}` MODIFY `foto_kolaborasi_tim` LONGTEXT NULL");
                }
                if (Schema::hasColumn($table, 'foto_kolaborasi_serah_terima')) {
                    DB::statement("ALTER TABLE `{$table}` MODIFY `foto_kolaborasi_serah_terima` LONGTEXT NULL");
                }
            }
        }
    }

    public function down(): void
    {
        // Tidak perlu dikembalikan ke VARCHAR(255) agar data Base64 tidak terpotong
    }
};