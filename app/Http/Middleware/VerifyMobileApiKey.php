<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyMobileApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = (string) config('services.mobile_api.key');

        if ($expectedKey === '') {
            return response()->json([
                'success' => false,
                'message' => 'Konfigurasi MOBILE_API_KEY server belum diatur.',
            ], 500);
        }

        $providedKey = (string) $request->header('X-API-KEY', '');

        if ($providedKey === '' || ! hash_equals($expectedKey, $providedKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: API Key tidak valid.',
            ], 401);
        }

        return $next($request);
    }
}