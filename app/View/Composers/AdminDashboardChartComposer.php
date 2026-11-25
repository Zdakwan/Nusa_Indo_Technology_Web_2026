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

    /**
     * Mengambil data grafik dari cache (3 menit) atau membangun ulang dari database.
     */
    public function getChartData(): array
    {
        return Cache::remember('admin_dashboard_charts_v3', now()->addMinutes(3), function () {
            return $this->buildChartData();
        });
    }

    private function buildChartData(): array
    {
        $months = collect(range(5, 0))->map(function (int $offset) {
            $date = Carbon::now()->subMonths($offset)->startOfMonth();

            return [
                'ym'    => $date->format('Y-m'),
                'label' => $date->translatedFormat('M Y'),
            ];
        });

        $chartLabels      = $months->pluck('label')->all();
        $chartTrendData   = array_fill(0, 6, 0);
        $chartBarMenunggu = array_fill(0, 6, 0);
        $chartBarDiproses = array_fill(0, 6, 0);
        $chartBarSelesai  = array_fill(0, 6, 0);
        $chartStatusData  = [
            'menunggu' => 0,
            'diproses' => 0,
            'selesai'  => 0,
        ];

        try {
            if (!Schema::hasTable('konsultasi')) {
                return compact(
                    'chartLabels',
                    'chartTrendData',
                    'chartStatusData',
                    'chartBarMenunggu',
                    'chartBarDiproses',
                    'chartBarSelesai'
                );
            }

            $dateColumn = Schema::hasColumn('konsultasi', 'created_at')
                ? 'created_at'
                : (Schema::hasColumn('konsultasi', 'tanggal_pengajuan') ? 'tanggal_pengajuan' : null);

            $hasStatus = Schema::hasColumn('konsultasi', 'status');
            $startDate = Carbon::now()->subMonths(5)->startOfMonth();

            // Agregasi tren & rincian status per bulan (6 bulan terakhir)
            if ($dateColumn !== null) {
                $monthlyRows = DB::table('konsultasi')
                    ->selectRaw("DATE_FORMAT({$dateColumn}, '%Y-%m') as ym")
                    ->selectRaw('COUNT(*) as total')
                    ->when($hasStatus, function ($query) {
                        $query->selectRaw("SUM(CASE WHEN LOWER(TRIM(status)) = 'menunggu' THEN 1 ELSE 0 END) as menunggu")
                              ->selectRaw("SUM(CASE WHEN LOWER(TRIM(status)) = 'diproses' THEN 1 ELSE 0 END) as diproses")
                              ->selectRaw("SUM(CASE WHEN LOWER(TRIM(status)) = 'selesai' THEN 1 ELSE 0 END) as selesai");
                    })
                    ->where($dateColumn, '>=', $startDate)
                    ->groupBy('ym')
                    ->get()
                    ->keyBy('ym');

                foreach ($months as $index => $item) {
                    $row = $monthlyRows->get($item['ym']);
                    if ($row) {
                        $chartTrendData[$index]   = (int) ($row->total ?? 0);
                        $chartBarMenunggu[$index] = (int) ($row->menunggu ?? 0);
                        $chartBarDiproses[$index] = (int) ($row->diproses ?? 0);
                        $chartBarSelesai[$index]  = (int) ($row->selesai ?? 0);
                    }
                }
            }

            // Agregasi proporsi status keseluruhan
            if ($hasStatus) {
                $statusRows = DB::table('konsultasi')
                    ->selectRaw('LOWER(TRIM(status)) as status_key, COUNT(*) as total')
                    ->groupBy('status_key')
                    ->pluck('total', 'status_key');

                $chartStatusData = [
                    'menunggu' => (int) ($statusRows['menunggu'] ?? 0),
                    'diproses' => (int) ($statusRows['diproses'] ?? 0),
                    'selesai'  => (int) ($statusRows['selesai'] ?? 0),
                ];
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
}