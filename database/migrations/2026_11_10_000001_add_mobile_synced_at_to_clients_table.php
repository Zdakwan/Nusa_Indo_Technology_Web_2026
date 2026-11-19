<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('clients', 'mobile_synced_at')) {
            Schema::table('clients', function (Blueprint $table) {
                // Terisi saat klien berhasil dikirim ke API mobile; NULL = belum tersinkron.
                $table->timestamp('mobile_synced_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('clients', 'mobile_synced_at')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('mobile_synced_at');
            });
        }
    }
};
