<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HandleSingleDeviceLogout
{
    public function handle(Logout $event): void
    {
        try {
            $user  = $event->user;
            $guard = $event->guard ?? config('auth.defaults.guard', 'web');
            $token = session()->pull("active_device_token_{$guard}");

            if ($user && $user->getAuthIdentifier()) {
                $query = DB::table('active_device_sessions')
                    ->where('authenticatable_type', get_class($user))
                    ->where('authenticatable_id', $user->getAuthIdentifier());

                if ($token) {
                    $query->where('session_token', $token);
                }

                $query->delete();
            }
        } catch (\Throwable $e) {
            Log::error('[SingleDeviceLogout] Gagal menghapus sesi perangkat: ' . $e->getMessage());
        }
    }
}