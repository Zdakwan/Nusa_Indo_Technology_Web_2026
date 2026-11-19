<?php

namespace App\Services;

use App\Models\ActiveDeviceSession;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeviceSessionService
{
    private const SESSION_KEY = 'active_device_token';

    /**
     * Mencoba mengklaim sesi perangkat baru secara atomik.
     * Mengembalikan ActiveDeviceSession lama jika akun sedang aktif di perangkat lain,
     * atau null jika klaim berhasil.
     */
    public function acquireOrGetExisting(Authenticatable $user, Request $request): ?ActiveDeviceSession
    {
        $currentToken = (string) $request->session()->get(self::SESSION_KEY, '');
        $ttlMinutes = (int) config('session.lifetime', 120);

        try {
            return DB::transaction(function () use ($user, $request, $currentToken, $ttlMinutes) {
                $existing = ActiveDeviceSession::where('authenticatable_type', $user::class)
                    ->where('authenticatable_id', $user->getAuthIdentifier())
                    ->lockForUpdate()
                    ->first();

                // Jika sesi lama masih aktif dan bukan milik browser ini, blokir login
                if ($existing && ! $existing->isExpired()) {
                    $isSameDevice = $currentToken !== '' && hash_equals($existing->session_token, $currentToken);

                    if (! $isSameDevice) {
                        return $existing;
                    }
                }

                // Hapus sesi kedaluwarsa atau perbarui token untuk perangkat yang sama
                $newToken = Str::random(64);
                $now = now();

                ActiveDeviceSession::updateOrCreate(
                    [
                        'authenticatable_type' => $user::class,
                        'authenticatable_id' => $user->getAuthIdentifier(),
                    ],
                    [
                        'session_token' => $newToken,
                        'ip_address' => $request->ip(),
                        'user_agent' => Str::limit((string) $request->userAgent(), 500),
                        'last_activity_at' => $now,
                        'expires_at' => $now->copy()->addMinutes($ttlMinutes),
                    ]
                );

                $request->session()->put(self::SESSION_KEY, $newToken);

                return null;
            });
        } catch (UniqueConstraintViolationException) {
            // Menangani race condition jika 2 perangkat menekan tombol Login di milidetik yang sama
            return ActiveDeviceSession::where('authenticatable_type', $user::class)
                ->where('authenticatable_id', $user->getAuthIdentifier())
                ->first();
        }
    }

    /**
     * Memperpanjang masa aktif sesi selama user beraktivitas dan memvalidasi token.
     */
    public function validateAndRefresh(Authenticatable $user, Request $request): bool
    {
        $currentToken = (string) $request->session()->get(self::SESSION_KEY, '');

        if ($currentToken === '') {
            return false;
        }

        $record = ActiveDeviceSession::where('authenticatable_type', $user::class)
            ->where('authenticatable_id', $user->getAuthIdentifier())
            ->first();

        if (! $record || $record->isExpired() || ! hash_equals($record->session_token, $currentToken)) {
            return false;
        }

        // Update DB maksimal sekali per 2 menit untuk menghemat query di Filess.io
        if ($record->last_activity_at->diffInSeconds(now()) >= 120) {
            $ttlMinutes = (int) config('session.lifetime', 120);
            $record->update([
                'last_activity_at' => now(),
                'expires_at' => now()->addMinutes($ttlMinutes),
            ]);
        }

        return true;
    }

    /**
     * Menghapus kunci perangkat saat user melakukan logout.
     */
    public function release(Authenticatable $user, Request $request): void
    {
        ActiveDeviceSession::where('authenticatable_type', $user::class)
            ->where('authenticatable_id', $user->getAuthIdentifier())
            ->delete();

        $request->session()->forget(self::SESSION_KEY);
    }

    public function maskEmail(?string $email): string
    {
        if (! $email || ! str_contains($email, '@')) {
            return '***';
        }

        [$name, $domain] = explode('@', $email, 2);
        $visible = mb_substr($name, 0, min(2, mb_strlen($name)));

        return $visible . str_repeat('*', max(3, mb_strlen($name) - 2)) . '@' . $domain;
    }
}
