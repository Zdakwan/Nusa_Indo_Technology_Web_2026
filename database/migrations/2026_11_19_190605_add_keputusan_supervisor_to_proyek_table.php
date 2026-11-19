<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up(): void
    {
        Schema::table('proyek', function (Blueprint $table) {
            $table->text('catatan_supervisor')->nullable()->after('status');
            $table->timestamp('keputusan_at')->nullable()->after('catatan_supervisor');
        });
    }

    public function down(): void
    {
        Schema::table('proyek', function (Blueprint $table) {
            $table->dropColumn(['catatan_supervisor', 'keputusan_at']);
        });
    }
};
