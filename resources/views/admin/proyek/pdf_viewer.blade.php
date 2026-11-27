<!DOCTYPE html>
{{--
==========================================================================
FILE: resources/views/admin/proyek/pdf_viewer.blade.php
==========================================================================
FUNGSI : Halaman preview PDF yang ditampilkan di browser sebelum user
         mengunduh. PDF ditampilkan dalam <iframe> menggunakan data base64.

ALUR KERJA:
   1. Admin klik tombol "Export PDF" di halaman detail proyek
   2. Controller (AdminProyekController@exportSinglePdf) generate PDF
   3. PDF dikonversi ke base64
   4. Halaman ini ditampilkan dengan PDF di dalam <iframe>
   5. User bisa klik "Unduh PDF" untuk mengunduh file

MENGAPA PAKAI VIEWER + IFRAME?
   Agar browser menampilkan preview terlebih dahulu, bukan langsung
   memicu unduhan otomatis dari Internet Download Manager (IDM).

VARIABEL YANG DITERIMA DARI CONTROLLER:
   $pdfBase64 → isi PDF dalam format base64 string
   $filename  → nama file saat diunduh (contoh: "Laporan-Rincian-WebLogistik.pdf")
   $proyek    → objek model Proyek (untuk judul toolbar)

CARA MENGUBAH TAMPILAN VIEWER:
   - Warna toolbar  → ubah background di CSS .toolbar
   - Judul toolbar  → ubah teks di <div class="title">
   - Warna tombol download → ubah background di CSS .btn-download
   - Tambah tombol baru → duplikat <a> tombol di dalam .toolbar

TEMPLATE PDF (isi laporan):
   resources/views/admin/proyek/single_pdf.blade.php
==========================================================================
--}}
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview PDF — {{ $proyek->nama_proyek }}</title>
    <style>
        /* Reset margin dan padding seluruh elemen */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* Layout utama: kolom vertikal, tinggi penuh layar */
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #1e3352;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* ─── TOOLBAR (bar navigasi di atas) ──────────────────────────── */
        /* UBAH WARNA LATAR TOOLBAR: ganti background di bawah */
        .toolbar {
            background: #1e3352;         /* ← WARNA TOOLBAR */
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-shrink: 0;
            border-bottom: 3px solid #f7bd55; /* Garis aksen kuning */
        }

        /* Judul di toolbar — terpotong (...) jika terlalu panjang */
        .toolbar .title {
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Nama proyek di dalam judul tampil dengan warna kuning */
        .toolbar .title span {
            color: #f7bd55; /* ← UBAH WARNA NAMA PROYEK DI TOOLBAR */
        }

        /* ─── TOMBOL UNDUH PDF ─────────────────────────────────────────── */
        /* UBAH WARNA TOMBOL DOWNLOAD: ganti background di bawah */
        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f7bd55;   /* ← WARNA TOMBOL DOWNLOAD */
            color: #1e3352;        /* ← WARNA TEKS TOMBOL DOWNLOAD */
            font-weight: 700;
            font-size: 13px;
            padding: 8px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.2s;
        }
        .btn-download:hover { background: #e6ac44; } /* Warna saat hover */

        /* ─── TOMBOL KEMBALI ───────────────────────────────────────────── */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.1);
            color: white;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.2s;
        }
        .btn-back:hover { background: rgba(255,255,255,0.2); }

        /* ─── IFRAME UNTUK MENAMPILKAN PDF ────────────────────────────── */
        /* Iframe mengisi seluruh sisa tinggi layar setelah toolbar */
        iframe {
            flex: 1;
            width: 100%;
            border: none;
        }
    </style>

    {{-- CSS responsive mobile --}}
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-nit.png?v=3') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon-nit.png?v=3') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon-nit.png?v=3') }}?v=2">
</head>
<body>

    {{-- ===================================================================
         TOOLBAR: Navigasi di bagian atas viewer
         Berisi: tombol kembali, judul proyek, dan tombol unduh
         =================================================================== --}}
    <div class="toolbar">

        {{-- Grup kiri: tombol kembali + judul --}}
        <div style="display:flex; align-items:center; gap:12px; min-width:0;">

            {{-- Tombol Kembali: kembali ke halaman sebelumnya --}}
            <a href="javascript:history.back()" class="btn-back">
                ← Kembali
            </a>

            {{-- Judul toolbar: tampilkan nama proyek
                 UBAH FORMAT TEKS DI SINI --}}
            <div class="title">
                Preview PDF — <span>{{ $proyek->nama_proyek }}</span>
            </div>
        </div>

        {{-- Tombol Unduh PDF:
             - href menggunakan data URI base64 untuk memicu unduhan langsung
             - download= menentukan nama file yang tersimpan di komputer user
             UBAH NAMA FILE: ganti $filename dari controller --}}
        <a href="data:application/pdf;base64,{{ $pdfBase64 }}"
           download="{{ $filename }}"
           class="btn-download">
            ⬇ Unduh PDF
        </a>
    </div>

    {{-- ===================================================================
         IFRAME: Tampilkan PDF di dalam frame
         Menggunakan data URI base64 agar tidak butuh URL eksternal.
         Browser akan render PDF langsung di sini.
         =================================================================== --}}
    <iframe
        src="data:application/pdf;base64,{{ $pdfBase64 }}"
        title="Preview PDF {{ $proyek->nama_proyek }}">
    </iframe>

    {{-- Script UX mobile (mis. deteksi orientasi, touch events) --}}
    <script src="{{ asset('js/mobile-ux.js') }}" defer></script>
</body>
</html>
