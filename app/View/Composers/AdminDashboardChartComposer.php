<?php

namespace App\View\Composers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminDashboardChartComposer
{
    public function compose(View $view): void
    {
        $view->with($this->getChartData());
    }

    public function getChartData(): array
    {
        return Cache::remember('admin_dashboard_charts_v6', now()->addMinutes(2), function () {
            return $this->buildChartData();
        });
    }

    private function normalizeStatus(?string $raw): ?string
    {
        if ($raw === null) {
            return null;
        }

        $s = strtolower(trim($raw));

        if (in_array($s, ['menunggu', 'pending', 'baru', 'waiting', 'antre', 'antri'], true)) {
            return 'menunggu';
        }

        if (in_array($s, ['diproses', 'proses', 'in_progress', 'in progress', 'processing', 'dikerjakan', 'approved', 'disetujui', 'diterima', 'scheduled', 'terjadwal'], true)) {
            return 'diproses';
        }

        if (in_array($s, ['selesai', 'completed', 'complete', 'done', 'finish', 'finished'], true)) {
            return 'selesai';
        }

        return null;
    }

    private function buildChartData(): array
    {
        $chartLabels      = [];
        $chartTrendData   = [];
        $chartBarMenunggu = [];
        $chartBarDiproses = [];
        $chartBarSelesai  = [];
        $chartStatusData  = [
            'menunggu' => 0,
            'diproses' => 0,
            'selesai'  => 0,
        ];

        $monthKeys = [];
        $monthIndexMap = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $ym = $date->format('Y-m');
            $idx = count($monthKeys);
            $monthKeys[] = $ym;
            $monthIndexMap[$ym] = $idx;
            $chartLabels[] = $date->translatedFormat('M Y');
            $chartTrendData[] = 0;
            $chartBarMenunggu[] = 0;
            $chartBarDiproses[] = 0;
            $chartBarSelesai[] = 0;
        }

        try {
            $table = $this->resolveConsultationTable();

            if ($table !== null) {
                $hasStatus = Schema::hasColumn($table, 'status');
                $dateCol = Schema::hasColumn($table, 'created_at')
                    ? 'created_at'
                    : (Schema::hasColumn($table, 'tanggal_pengajuan') ? 'tanggal_pengajuan' : null);

                $rows = DB::table($table)->get();

                foreach ($rows as $row) {
                    $statusKey = $hasStatus ? $this->normalizeStatus((string) ($row->status ?? '')) : null;

                    if ($statusKey !== null) {
                        $chartStatusData[$statusKey]++;
                    }

                    if ($dateCol !== null && !empty($row->{$dateCol})) {
                        try {
                            $ym = Carbon::parse($row->{$dateCol})->format('Y-m');
                            if (isset($monthIndexMap[$ym])) {
                                $pos = $monthIndexMap[$ym];
                                $chartTrendData[$pos]++;

                                if ($statusKey === 'menunggu') {
                                    $chartBarMenunggu[$pos]++;
                                } elseif ($statusKey === 'diproses') {
                                    $chartBarDiproses[$pos]++;
                                } elseif ($statusKey === 'selesai') {
                                    $chartBarSelesai[$pos]++;
                                }
                            }
                        } catch (\Throwable $ignoreDate) {
                            // Abaikan tanggal tidak valid
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('[AdminDashboardChart] Gagal memuat data grafik: ' . $e->getMessage());
        }

        return compact(
            'chartLabels',
            'chartTrendData',
            'chartStatusData',
            'chartBarMenunggu',
            'chartBarDiproses',
            'chartBarSelesai'
        );
    }

    private function resolveConsultationTable(): ?string
    {
        $candidates = [
            'konsultasis', 'konsultasi', 'consultations',
            'permintaan_konsultasis', 'permintaan_konsultasi',
            'aktivitas_kliens', 'aktivitas_klien', 'proyeks', 'projects',
        ];

        foreach ($candidates as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        $allTables = array_map('current', DB::select('SHOW TABLES'));
        foreach ($allTables as $table) {
            if (preg_match('/(konsul|consult|aktivit|proyek|project|permintaan)/i', $table)) {
                return $table;
            }
        }

        return null;
    }
}