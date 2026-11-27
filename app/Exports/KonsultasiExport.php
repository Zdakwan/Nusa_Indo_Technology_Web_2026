<?php

/*
|==========================================================================
| FILE: app/Exports/KonsultasiExport.php
|==========================================================================
| FUNGSI  : Class utama yang menentukan struktur file Excel (.xlsx) untuk
|           laporan permintaan konsultasi.
|
| FILE EXCEL YANG DIHASILKAN MEMILIKI 2 SHEET (TAB):
|   Sheet 1 → KonsultasiGrafikSheet   : Data angka ringkasan grafik
|             (tren 6 bulan + distribusi status)
|   Sheet 2 → KonsultasiDetailSheet  : Tabel detail lengkap semua konsultasi
|
| CARA MENGUBAH ISI EXCEL:
|   - Tambah/ubah kolom Sheet 2  → edit: app/Exports/Sheets/KonsultasiDetailSheet.php
|   - Ubah data grafik Sheet 1   → edit: app/Exports/Sheets/KonsultasiGrafikSheet.php
|   - Tambah sheet baru          → tambahkan class baru di array sheets() di bawah
|
| DIPANGGIL DARI:
|   app/Http/Controllers/Admin/DashboardController.php → method exportExcel()
|   Excel::download(new KonsultasiExport(), 'nama-file.xlsx')
|
| LIBRARY:
|   maatwebsite/excel (Laravel Excel v4)
|   - WithMultipleSheets : Buat lebih dari 1 tab dalam 1 file xlsx
|   - Export             : Interface wajib di Laravel Excel v4
|==========================================================================
*/

namespace App\Exports;

use App\Exports\Sheets\KonsultasiDetailSheet;
use App\Exports\Sheets\KonsultasiGrafikSheet;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KonsultasiExport implements Export, WithMultipleSheets
{
    /**
     * Kembalikan daftar sheet yang akan ada di dalam file Excel.
     *
     * Urutan array di sini = urutan tab sheet dari kiri ke kanan.
     *
     * Cara menambah sheet baru:
     *   1. Buat file baru di app/Exports/Sheets/NamaSheet.php
     *   2. Tambahkan: new NamaSheet() di array ini
     *
     * @return array
     */
    public function sheets(): array
    {
        return [
            new KonsultasiGrafikSheet(), // Sheet 1: Angka ringkasan (tren 6 bulan & distribusi status)
            new KonsultasiDetailSheet(), // Sheet 2: Tabel detail lengkap seluruh konsultasi
        ];
    }
}
