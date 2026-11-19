<?php

namespace App\Http\Middleware;

use App\Services\DeviceSessionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSingleDeviceSession
{
    public function __construct(
        private readonly DeviceSessionService $deviceSessionService
    ) {}

    public function handle(Request $request, Closure $next, string $guard = 'client'): Response
    {
        $authGuard = Auth::guard($guard);

        if ($authGuard->check()) {
            $user = $authGuard->user();

            if (! $this->deviceSessionService->validateAndRefresh($user, $request)) {
                $authGuard->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $loginRoute = $guard === 'admin' ? 'admin.login' : 'login';

                return redirect()->route($loginRoute)->withErrors([
                    'email' => 'Sesi Anda telah berakhir. Silakan login kembali.',
                ]);
            }
        }

        return $next($request);
    }
}
