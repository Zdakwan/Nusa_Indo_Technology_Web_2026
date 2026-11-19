<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use Illuminate\Support\Facades\DB;

class LoginLogController extends Controller
{
    public function index()
    {
        $logs = LoginLog::latest()->get();

        // Kumpulkan email unik untuk batch query agar tidak terjadi N+1 query
        $clientEmails = $logs
            ->filter(fn ($log) => strtolower((string) $log->role) === 'client')
            ->pluck('email')
            ->filter()
            ->unique()
            ->values();

        $adminEmails = $logs
            ->filter(fn ($log) => strtolower((string) $log->role) !== 'client')
            ->pluck('email')
            ->filter()
            ->unique()
            ->values();

        // Normalisasi key email ke lowercase agar pencocokan case-insensitive di PHP
        $clientNames = $clientEmails->isNotEmpty()
            ? DB::table('clients')
                ->whereIn('email', $clientEmails)
                ->pluck('name', 'email')
                ->mapWithKeys(fn ($name, $email) => [strtolower((string) $email) => $name])
            : collect();

        $adminNames = $adminEmails->isNotEmpty()
            ? DB::table('admins')
                ->whereIn('email', $adminEmails)
                ->pluck('name', 'email')
                ->mapWithKeys(fn ($name, $email) => [strtolower((string) $email) => $name])
            : collect();

        $logs->transform(function ($log) use ($clientNames, $adminNames) {
            $emailKey = strtolower((string) $log->email);
            $isClient = strtolower((string) $log->role) === 'client';

            $log->nama = $isClient
                ? ($clientNames->get($emailKey) ?? '-')
                : ($adminNames->get($emailKey) ?? '-');

            return $log;
        });

        return view('admin.pengguna.riwayat-login', compact('logs'));
    }
}
