<?php

namespace App\Exports\Sheets;

use App\View\Composers\AdminDashboardChartComposer;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KonsultasiGrafikSheet implements FromArray, WithTitle, WithStyles, ShouldAutoSize, WithCharts
{
    private array $chartData;

    public function __construct(array $chartData = [])
    {
        $this->chartData = !empty($chartData)
            ? $chartData
            : (new AdminDashboardChartComposer())->getChartData();
    }

    public function title(): string
    {
        return 'Ringkasan Grafik';
    }

    public function array(): array
    {
        $status      = $this->chartData['chartStatusData'] ?? ['menunggu' => 0, 'diproses' => 0, 'selesai' => 0];
        $menungguAll = (int) ($status['menunggu'] ?? 0);
        $diprosesAll = (int) ($status['diproses'] ?? 0);
        $selesaiAll  = (int) ($status['selesai'] ?? 0);
        $grandTotal  = $menungguAll + $diprosesAll + $selesaiAll;

        $pct = fn (int $val): string => $grandTotal > 0
            ? number_format(($val / $grandTotal) * 100, 1) . '%'
            : '0.0%';

        $rows = [
            ['LAPORAN RINGKASAN & GRAFIK PERMINTAAN KONSULTASI'],
            ['Dicetak pada: ' . now()->format('d M Y H:i') . ' WIB'],
            [''],
            ['DISTRIBUSI STATUS KESELURUHAN', 'Jumlah', 'Persentase'],
            ['Menunggu', $menungguAll, $pct($menungguAll)],
            ['Diproses', $diprosesAll, $pct($diprosesAll)],
            ['Selesai', $selesaiAll, $pct($selesaiAll)],
            ['Total Keseluruhan', $grandTotal, $grandTotal > 0 ? '100.0%' : '0.0%'],
            [''],
            ['PERBANDINGAN STATUS & TREN BULANAN (6 BULAN TERAKHIR)'],
            ['Bulan', 'Menunggu', 'Diproses', 'Selesai', 'Total Pengajuan'],
        ];

        $labels      = $this->chartData['chartLabels'] ?? [];
        $barMenunggu = $this->chartData['chartBarMenunggu'] ?? [];
        $barDiproses = $this->chartData['chartBarDiproses'] ?? [];
        $barSelesai  = $this->chartData['chartBarSelesai'] ?? [];
        $trendData   = $this->chartData['chartTrendData'] ?? [];

        $sumMenunggu = 0;
        $sumDiproses = 0;
        $sumSelesai  = 0;
        $sumTrend    = 0;

        foreach ($labels as $idx => $label) {
            $m = (int) ($barMenunggu[$idx] ?? 0);
            $d = (int) ($barDiproses[$idx] ?? 0);
            $s = (int) ($barSelesai[$idx] ?? 0);
            $t = (int) ($trendData[$idx] ?? ($m + $d + $s));

            $sumMenunggu += $m;
            $sumDiproses += $d;
            $sumSelesai  += $s;
            $sumTrend    += $t;

            $rows[] = [$label, $m, $d, $s, $t];
        }

        $rows[] = ['Total 6 Bulan', $sumMenunggu, $sumDiproses, $sumSelesai, $sumTrend];

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $rowCount = max(1, count($this->chartData['chartLabels'] ?? []));
        $endRow   = 11 + $rowCount;
        $totalRow = $endRow + 1;

        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A10:E10');

        // Border untuk Tabel Status (A4:C8) dan Tabel Bulanan (A11:E{$totalRow})
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'CBD5E1'],
                ],
            ],
        ];
        $sheet->getStyle('A4:C8')->applyFromArray($borderStyle);
        $sheet->getStyle("A11:E{$totalRow}")->applyFromArray($borderStyle);
        $sheet->getStyle('B5:B8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C5:C8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B12:E{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1B314C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        return [
            1 => ['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1B314C']]],
            2 => ['font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '64748B']]],
            4 => $headerStyle,
            8 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']]],
            10 => ['font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '1B314C']]],
            11 => $headerStyle,
            $totalRow => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']]],
        ];
    }

    public function charts(): array
    {
        $sheetName = "'" . $this->title() . "'";
        $rowCount  = max(1, count($this->chartData['chartLabels'] ?? []));
        $endRow    = 11 + $rowCount;

        // 1. Diagram Batang (Bar/Column Chart): Perbandingan Status per Bulan
        $barLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "{$sheetName}!\$B\$11", null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "{$sheetName}!\$C\$11", null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "{$sheetName}!\$D\$11", null, 1),
        ];
        $barCategories = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "{$sheetName}!\$A\$12:\$A\${$endRow}", null, $rowCount),
        ];
        $barValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "{$sheetName}!\$B\$12:\$B\${$endRow}", null, $rowCount),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "{$sheetName}!\$C\$12:\$C\${$endRow}", null, $rowCount),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "{$sheetName}!\$D\$12:\$D\${$endRow}", null, $rowCount),
        ];

        $barSeries = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            range(0, count($barValues) - 1),
            $barLabels,
            $barCategories,
            $barValues,
            DataSeries::DIRECTION_COL
        );
        $barChart = new Chart(
            'bar_chart_status',
            new Title('Perbandingan Status Konsultasi per Bulan'),
            new Legend(Legend::POSITION_TOP, null, false),
            new PlotArea(null, [$barSeries])
        );
        $barChart->setTopLeftPosition('G4');
        $barChart->setBottomRightPosition('N18');

        // 2. Grafik Garis (Line Chart): Tren Permintaan Konsultasi
        $lineLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "{$sheetName}!\$E\$11", null, 1),
        ];
        $lineValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "{$sheetName}!\$E\$12:\$E\${$endRow}", null, $rowCount),
        ];
        $lineSeries = new DataSeries(
            DataSeries::TYPE_LINECHART,
            DataSeries::GROUPING_STANDARD,
            [0],
            $lineLabels,
            $barCategories,
            $lineValues
        );
        $lineChart = new Chart(
            'line_chart_trend',
            new Title('Tren Permintaan Konsultasi (6 Bulan Terakhir)'),
            new Legend(Legend::POSITION_BOTTOM, null, false),
            new PlotArea(null, [$lineSeries])
        );
        $lineChart->setTopLeftPosition('G20');
        $lineChart->setBottomRightPosition('N34');

        // 3. Grafik Lingkaran (Pie Chart): Distribusi Status Keseluruhan
        $pieLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "{$sheetName}!\$B\$4", null, 1),
        ];
        $pieCategories = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "{$sheetName}!\$A\$5:\$A\$7", null, 3),
        ];
        $pieValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "{$sheetName}!\$B\$5:\$B\$7", null, 3),
        ];
        $pieSeries = new DataSeries(
            DataSeries::TYPE_PIECHART,
            null,
            [0],
            $pieLabels,
            $pieCategories,
            $pieValues
        );
        $pieChart = new Chart(
            'pie_chart_status',
            new Title('Distribusi Status Keseluruhan'),
            new Legend(Legend::POSITION_BOTTOM, null, false),
            new PlotArea(null, [$pieSeries])
        );
        $pieChart->setTopLeftPosition('G36');
        $pieChart->setBottomRightPosition('N50');

        return [$barChart, $lineChart, $pieChart];
    }
}