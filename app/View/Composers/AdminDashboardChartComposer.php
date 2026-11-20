<?php

namespace App\View\Composers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminDashboardChartComposer
{
    public function compose(View $view): void
    {
        $chartLabels = [];
        $chartTrendData = [];
        $chartStatusData = [
            'menunggu' => 0,
            'diproses' => 0,
            'selesai'  => 0,
        ];

        // Siapkan kerangka 6 bulan terakhir agar bulan kosong tetap bernilai 0
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->put($date->format('Y-m'), [
                'label' => $date->translatedFormat('M Y'),
                'count' => 0,
            ]);
        }

        try {
            $table = $this->resolveConsultationTable();

            if ($table !== null) {
                $monthlyRows = DB::table($table)
                    ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS total")
                    ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
                    ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                    ->pluck('total', 'ym');

                foreach ($months as $ym => $item) {
                    $chartLabels[] = $item['label'];
                    $chartTrendData[] = (int) ($monthlyRows[$ym] ?? 0);
                }

                if (Schema::hasColumn($table, 'status')) {
                    $statusRows = DB::table($table)
                        ->selectRaw('LOWER(TRIM(status)) AS status_key, COUNT(*) AS total')
                        ->groupBy(DB::raw('LOWER(TRIM(status))'))
                        ->pluck('total', 'status_key');

                    $chartStatusData = [
                        'menunggu' => (int) (($statusRows['menunggu'] ?? 0) + ($statusRows['pending'] ?? 0)),
                        'diproses' => (int) (($statusRows['diproses'] ?? 0) + ($statusRows['proses'] ?? 0) + ($statusRows['in_progress'] ?? 0)),
                        'selesai'  => (int) (($statusRows['selesai'] ?? 0) + ($statusRows['completed'] ?? 0) + ($statusRows['done'] ?? 0)),
                    ];
                }
            } else {
                foreach ($months as $item) {
                    $chartLabels[] = $item['label'];
                    $chartTrendData[] = 0;
                }
            }
        } catch (\Throwable $e) {
            Log::error('[AdminDashboardChart] Gagal memuat data grafik: ' . $e->getMessage());
            foreach ($months as $item) {
                $chartLabels[] = $item['label'];
                $chartTrendData[] = 0;
            }
        }

        $view->with(compact('chartLabels', 'chartTrendData', 'chartStatusData'));
    }

    private function resolveConsultationTable(): ?string
    {
        $candidates = ['konsultasis', 'consultations', 'permintaan_konsultasis', 'proyeks', 'projects'];

        foreach ($candidates as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'created_at')) {
                return $table;
            }
        }

        return null;
    }
}