<?php

namespace App\Listeners;

use App\Models\ActiveDeviceSession;
use Illuminate\Auth\Events\Logout;

class HandleSingleDeviceLogout
{
    public function handle(Logout $event): void
    {
        if (! $event->user || ! in_array($event->guard, ['client', 'admin'], true)) {
            return;
        }

        $request = request();

        if (! $request || ! $request->hasSession()) {
            return;
        }

        $currentToken = (string) $request->session()->get('active_device_token', '');

        if ($currentToken !== '') {
            // Validasi token agar logout dari sesi tidak sah tidak menghapus kunci perangkat aktif
            ActiveDeviceSession::where('authenticatable_type', $event->user::class)
                ->where('authenticatable_id', $event->user->getAuthIdentifier())
                ->where('session_token', $currentToken)
                ->delete();

            $request->session()->forget('active_device_token');
        }
    }
}