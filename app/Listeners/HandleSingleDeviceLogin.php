<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HandleSingleDeviceLogin
{
    public function handle(Login $event): void
    {
        try {
            $user  = $event->user;
            $guard = $event->guard ?? config('auth.defaults.guard', 'web');

            if (!$user || !$user->getAuthIdentifier()) {
                return;
            }

            // Generate token acak unik agar tidak pernah bentrok antar guard di browser yang sama
            $token = Str::random(64);
            $now   = now();
            $ttl   = (int) config('session.lifetime', 120);

            DB::table('active_device_sessions')->updateOrInsert(
                [
                    'authenticatable_type' => get_class($user),
                    'authenticatable_id'   => $user->getAuthIdentifier(),
                ],
                [
                    'session_token'    => $token,
                    'ip_address'       => request()->ip(),
                    'user_agent'       => Str::limit((string) request()->userAgent(), 500, ''),
                    'last_activity_at' => $now,
                    'expires_at'       => $now->copy()->addMinutes($ttl),
                    'updated_at'       => $now,
                    'created_at'       => $now,
                ]
            );

            // Simpan token terpisah per guard agar Admin & Client tidak saling menimpa
            session()->put("active_device_token_{$guard}", $token);
        } catch (\Throwable $e) {
            Log::error('[SingleDeviceLogin] Gagal menyimpan sesi perangkat: ' . $e->getMessage());
        }
    }
}