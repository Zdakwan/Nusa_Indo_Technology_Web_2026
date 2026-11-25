<?php

namespace App\Exports;

use App\Exports\Sheets\KonsultasiDetailSheet;
use App\Exports\Sheets\KonsultasiGrafikSheet;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Class utama untuk export Excel Laporan Permintaan Konsultasi.
 *
 * - Implements `Export`               : Wajib di Laravel Excel v4 agar class ini dikenali sebagai objek export.
 * - Implements `WithMultipleSheets`   : Memungkinkan 1 file .xlsx memiliki lebih dari 1 sheet (tab).
 */
class KonsultasiExport implements Export, WithMultipleSheets
{
    /**
     * Mengembalikan daftar sheet yang akan dibuat di dalam file Excel.
     * Urutan array di sini menentukan urutan tab sheet dari kiri ke kanan.
     */
    public function sheets(): array
    {
        return [
            new KonsultasiGrafikSheet(), // Sheet 1: Ringkasan angka grafik (tren 6 bulan & distribusi status)
            new KonsultasiDetailSheet(), // Sheet 2: Tabel detail lengkap seluruh data konsultasi
        ];
    }
}

