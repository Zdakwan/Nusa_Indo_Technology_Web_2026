<?php

namespace App\Listeners;

use App\Services\DeviceSessionService;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class HandleSingleDeviceLogin
{
    public function __construct(
        private readonly DeviceSessionService $deviceSession
    ) {}

    public function handle(Login $event): void
    {
        if (! in_array($event->guard, ['client', 'admin'], true)) {
            return;
        }

        $request = request();

        if (! $request || ! $request->hasSession()) {
            return;
        }

        $existingSession = $this->deviceSession->acquireOrGetExisting($event->user, $request);

        if ($existingSession !== null) {
            // Cabut autentikasi di perangkat baru tanpa menghapus sesi perangkat lama
            $guard = Auth::guard($event->guard);
            if (method_exists($guard, 'logoutCurrentDevice')) {
                $guard->logoutCurrentDevice();
            } else {
                $guard->logout();
            }

            $lockInfo = [
                'role' => $event->guard === 'admin' ? 'Admin' : 'Klien',
                'email' => $this->deviceSession->maskEmail($event->user->email ?? null),
                'device' => $existingSession->device_summary,
                'ip' => $existingSession->ip_address,
                'last_active' => $existingSession->last_activity_at->diffForHumans(),
                'login_url' => route($event->guard === 'admin' ? 'admin.login' : 'login'),
            ];

            throw new HttpResponseException(
                redirect()
                    ->route('auth.device-locked')
                    ->with('device_lock_info', $lockInfo)
            );
        }
    }
}