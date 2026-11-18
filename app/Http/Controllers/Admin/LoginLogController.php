<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use Illuminate\Support\Facades\DB;

class LoginLogController extends Controller
{
    public function index()
    {
        // Ambil log beserta nama dari tabel admins atau clients berdasarkan role
        $logs = LoginLog::latest()->get()->map(function ($log) {
            if (strtolower($log->role) === 'client') {
                $user = DB::table('clients')->where('email', $log->email)->first();
            } else {
                $user = DB::table('admins')->where('email', $log->email)->first();
            }
            $log->nama = $user->name ?? '-';
            return $log;
        });

        return view('admin.pengguna.riwayat-login', compact('logs'));
    }
}
