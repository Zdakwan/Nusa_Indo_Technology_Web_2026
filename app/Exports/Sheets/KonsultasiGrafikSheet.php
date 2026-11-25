<?php

namespace App\Exports\Sheets;

use App\Models\Konsultasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Sheet 1: Ringkasan Grafik Permintaan Konsultasi.
 *
 * Interface yang digunakan:
 * - Export         : Penanda wajib objek export di Laravel Excel v4.
 * - FromArray      : Sumber data sheet berasal dari array PHP (cocok untuk laporan ringkasan/rekap).
 * - WithTitle      : Mengatur nama tab sheet di Excel.
 * - ShouldAutoSize : Lebar kolom otomatis menyesuaikan isi teks terpanjang.
 * - WithStyles     : Memberikan styling (warna header, bold, border, merge cell) via PhpSpreadsheet.
 */
class KonsultasiGrafikSheet implements Export, FromArray, WithTitle, ShouldAutoSize, WithStyles
{
    private int $trendHeaderRow = 6;
    private int $trendEndRow = 13;
    private int $statusHeaderRow = 16;
    private int $statusEndRow = 21;

    public function title(): string
    {
        return 'Ringkasan Grafik';
    }

    public function array(): array
    {
        $now = Carbon::now();
        $startPeriod = $now->copy()->subMonths(5)->startOfMonth();

        $monthlyRows = DB::table('konsultasi')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS total")
            ->where('created_at', '>=', $startPeriod)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->pluck('total', 'ym');

        $rows = [
            ['LAPORAN RINGKASAN GRAFIK PERMINTAAN KONSULTASI', '', ''],
            ['Tanggal Export', $now->translatedFormat('d F Y H:i'), ''],
            ['Periode Tren Grafik', $startPeriod->translatedFormat('M Y') . ' - ' . $now->translatedFormat('M Y') . ' (6 Bulan Terakhir)', ''],
            ['', '', ''],
            ['TREN PERMINTAAN KONSULTASI (6 BULAN TERAKHIR)', '', ''],
            ['No', 'Bulan', 'Jumlah Permintaan'],
        ];

        $this->trendHeaderRow = 6;
        $totalSixMonths = 0;

        for ($i = 5, $no = 1; $i >= 0; $i--, $no++) {
            $date = $now->copy()->subMonths($i);
            $ym = $date->format('Y-m');
            $count = (int) ($monthlyRows[$ym] ?? 0);
            $totalSixMonths += $count;

            $rows[] = [$no, $date->translatedFormat('M Y'), $count];
        }

        $rows[] = ['', 'Total 6 Bulan Terakhir', $totalSixMonths];
        $this->trendEndRow = count($rows);

        $rows[] = ['', '', ''];
        $rows[] = ['DISTRIBUSI STATUS PERMINTAAN (SELURUH DATA)', '', ''];
        $rows[] = ['Status', 'Jumlah', 'Persentase (%)'];
        $this->statusHeaderRow = count($rows);

        $statusRows = Konsultasi::query()
            ->selectRaw('LOWER(TRIM(status)) AS status_key, COUNT(*) AS total')
            ->groupBy(DB::raw('LOWER(TRIM(status))'))
            ->pluck('total', 'status_key');

        $menunggu = (int) (($statusRows['menunggu'] ?? 0) + ($statusRows['pending'] ?? 0));
        $diproses = (int) (($statusRows['diproses'] ?? 0) + ($statusRows['proses'] ?? 0) + ($statusRows['in_progress'] ?? 0) + ($statusRows['approved'] ?? 0));
        $selesai  = (int) (($statusRows['selesai'] ?? 0) + ($statusRows['completed'] ?? 0) + ($statusRows['done'] ?? 0));
        $ditolak  = (int) (($statusRows['ditolak'] ?? 0) + ($statusRows['rejected'] ?? 0));
        $totalAll = $menunggu + $diproses + $selesai + $ditolak;

        $pct = fn (int $val): string => $totalAll > 0
            ? number_format(($val / $totalAll) * 100, 1) . '%'
            : '0.0%';

        $rows[] = ['Menunggu', $menunggu, $pct($menunggu)];
        $rows[] = ['Diproses / Disetujui', $diproses, $pct($diproses)];
        $rows[] = ['Selesai', $selesai, $pct($selesai)];
        $rows[] = ['Ditolak', $ditolak, $pct($ditolak)];
        $rows[] = ['Total Keseluruhan', $totalAll, $totalAll > 0 ? '100.0%' : '0.0%'];

        $this->statusEndRow = count($rows);

        return $rows;
    }

    public function styles(Worksheet $sheet): ?array
    {
        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A5:C5');
        $sheet->mergeCells('A' . ($this->statusHeaderRow - 1) . ':C' . ($this->statusHeaderRow - 1));

        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
        ];

        $sheet->getStyle("A{$this->trendHeaderRow}:C{$this->trendEndRow}")->applyFromArray($borderStyle);
        $sheet->getStyle("A{$this->statusHeaderRow}:C{$this->statusEndRow}")->applyFromArray($borderStyle);

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '23364F'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        $sheet->getStyle("A{$this->trendHeaderRow}:C{$this->trendHeaderRow}")->applyFromArray($headerStyle);
        $sheet->getStyle("A{$this->statusHeaderRow}:C{$this->statusHeaderRow}")->applyFromArray($headerStyle);

        $totalRowStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F1F5F9'],
            ],
        ];

        $sheet->getStyle("A{$this->trendEndRow}:C{$this->trendEndRow}")->applyFromArray($totalRowStyle);
        $sheet->getStyle("A{$this->statusEndRow}:C{$this->statusEndRow}")->applyFromArray($totalRowStyle);

        return [
            1 => ['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1E293B']]],
            2 => ['font' => ['size' => 10, 'color' => ['rgb' => '475569']]],
            3 => ['font' => ['size' => 10, 'color' => ['rgb' => '475569']]],
            5 => ['font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '23364F']]],
            ($this->statusHeaderRow - 1) => ['font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '23364F']]],
        ];
    }
}
