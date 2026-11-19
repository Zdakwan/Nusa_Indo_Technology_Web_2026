<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveDeviceSession extends Model
{
    protected $fillable = [
        'authenticatable_type',
        'authenticatable_id',
        'session_token',
        'ip_address',
        'user_agent',
        'last_activity_at',
        'expires_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Ringkasan nama OS & browser agar mudah dikenali user di halaman peringatan.
     */
    public function getDeviceSummaryAttribute(): string
    {
        $ua = (string) $this->user_agent;

        $os = match (true) {
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'iPhone'), str_contains($ua, 'iPad') => 'iOS',
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'Mac OS') => 'macOS',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'Perangkat Tidak Dikenal',
        };

        $browser = match (true) {
            str_contains($ua, 'Edg') => 'Edge',
            str_contains($ua, 'OPR'), str_contains($ua, 'Opera') => 'Opera',
            str_contains($ua, 'Chrome') => 'Chrome',
            str_contains($ua, 'Firefox') => 'Firefox',
            str_contains($ua, 'Safari') => 'Safari',
            default => 'Browser Lain',
        };

        return "{$browser} di {$os}";
    }
}
