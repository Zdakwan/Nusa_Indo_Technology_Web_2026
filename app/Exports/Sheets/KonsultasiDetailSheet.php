<?php

namespace App\Exports\Sheets;

use App\Models\Konsultasi;
use Carbon\Carbon;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Sheet 2: Detail Lengkap Seluruh Permintaan Konsultasi.
 */
class KonsultasiDetailSheet implements Export, FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private int $rowNumber = 0;

    public function title(): string
    {
        return 'Detail Konsultasi';
    }

    public function collection(): Enumerable
    {
        return Konsultasi::with(['client', 'proyek'])
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'ID Konsultasi',
            'Tanggal Pengajuan',
            'Periode Bulan',
            'Nama Klien',
            'Email Klien',
            'No. Telp Klien',
            'Nama Perusahaan',
            'Nama PIC',
            'Jabatan PIC',
            'Email PIC',
            'Telepon PIC',
            'Jenis Konsultasi',
            'Tanggal Konsultasi',
            'Waktu Konsultasi',
            'Metode',
            'Konsultan',
            'Link Meeting',
            'Status',
            'Proyek Terkait',
            'Progres Proyek',
            'Catatan / Permintaan Klien',
            'Pesan Admin',
        ];
    }

    public function map($konsultasi): array
    {
        $this->rowNumber++;

        $createdAt = $konsultasi->created_at ? Carbon::parse($konsultasi->created_at) : null;
        $client = $konsultasi->client;
        $proyek = $konsultasi->proyek;

        $perusahaan = $konsultasi->nama_perusahaan
            ?? $client?->company
            ?? $client?->nama_perusahaan
            ?? '-';

        $telpKlien = $client?->no_telp ?? $client?->phone ?? '-';

        $statusLabel = match (strtolower(trim((string) $konsultasi->status))) {
            'pending', 'menunggu'                           => 'Menunggu',
            'approved', 'diproses', 'proses', 'in_progress' => 'Diproses / Disetujui',
            'completed', 'selesai', 'done'                  => 'Selesai',
            'rejected', 'ditolak'                           => 'Ditolak',
            default                                         => (string) ($konsultasi->status ?: '-'),
        };

        return [
            $this->rowNumber,
            $konsultasi->id,
            $createdAt ? $createdAt->format('d/m/Y H:i') : '-',
            $createdAt ? $createdAt->translatedFormat('M Y') : '-',
            $client?->name ?? '-',
            $client?->email ?? '-',
            $telpKlien,
            $perusahaan,
            $konsultasi->nama_pic ?? '-',
            $konsultasi->jabatan_pic ?? '-',
            $konsultasi->email_pic ?? '-',
            $konsultasi->telepon_pic ?? '-',
            $konsultasi->jenis_konsultasi ?? '-',
            $konsultasi->tanggal_konsultasi
                ? Carbon::parse($konsultasi->tanggal_konsultasi)->format('d/m/Y')
                : '-',
            $konsultasi->waktu_konsultasi ?? '-',
            $konsultasi->metode ?? '-',
            $konsultasi->konsultan ?? '-',
            $konsultasi->link_meeting ?? '-',
            $statusLabel,
            $proyek?->nama_proyek ?? '-',
            $proyek ? ($proyek->progres . '%') : '-',
            $konsultasi->catatan_klien ?? '-',
            $konsultasi->pesan_admin ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '23364F'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        if ($highestRow >= 1) {
            $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CBD5E1'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                ],
            ]);
        }

        $sheet->freezePane('A2');

        return [];
    }
}
