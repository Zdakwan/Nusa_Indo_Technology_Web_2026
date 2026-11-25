<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('active_device_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('authenticatable_type');
            $table->unsignedBigInteger('authenticatable_id');
            $table->string('session_token', 64)->unique();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('last_activity_at');
            $table->timestamp('expires_at')->index();
            $table->timestamps();

            // Menjamin maksimal 1 perangkat aktif per akun di level database
            $table->unique(['authenticatable_type', 'authenticatable_id'], 'unique_active_device_per_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('active_device_sessions');
    }
};
