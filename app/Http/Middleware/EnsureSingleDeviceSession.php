<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureSingleDeviceSession
{
    public function handle(Request $request, Closure $next, ?string $guard = null): Response
    {
        $activeGuard = $guard ?? ($request->is('admin', 'admin/*') ? 'admin' : config('auth.defaults.guard', 'web'));

        if (!Auth::guard($activeGuard)->check()) {
            return $next($request);
        }

        $user  = Auth::guard($activeGuard)->user();
        $token = $request->session()->get("active_device_token_{$activeGuard}");

        $activeSession = DB::table('active_device_sessions')
            ->where('authenticatable_type', get_class($user))
            ->where('authenticatable_id', $user->getAuthIdentifier())
            ->first();

        if (!$token || !$activeSession || !hash_equals((string) $activeSession->session_token, (string) $token)) {
            Auth::guard($activeGuard)->logout();
            $request->session()->forget("active_device_token_{$activeGuard}");

            $loginRoute = $activeGuard === 'admin' ? 'admin.login' : 'login';

            return redirect()->route($loginRoute)->withErrors([
                'email' => 'Sesi Anda telah berakhir karena login di perangkat lain. Silakan login kembali.',
            ]);
        }

        return $next($request);
    }
}