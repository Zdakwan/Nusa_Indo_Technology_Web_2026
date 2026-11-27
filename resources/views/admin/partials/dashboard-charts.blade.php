{{--
==========================================================================
FILE: resources/views/admin/partials/dashboard-charts.blade.php
==========================================================================
FUNGSI : Komponen partial (potongan halaman) yang berisi:
           1. HTML canvas untuk 3 jenis chart/diagram
           2. Script Chart.js untuk render semua chart

DI-INCLUDE OLEH:
   resources/views/admin/dashboard.blade.php

DATA YANG DITERIMA DARI CONTROLLER (DashboardController@index):
   $chartLabels       â†’ array label bulan, contoh: ['Jun', 'Jul', 'Agu', ...]
   $chartTrendData    â†’ array jumlah permintaan per bulan (untuk grafik garis)
   $chartStatusData   â†’ array {menunggu, diproses, selesai} untuk grafik donat
   $chartBarMenunggu  â†’ array jumlah status 'menunggu' per bulan (grafik batang)
   $chartBarDiproses  â†’ array jumlah status 'diproses' per bulan
   $chartBarSelesai   â†’ array jumlah status 'selesai' per bulan

LIBRARY YANG DIGUNAKAN:
   Chart.js v4.4.3 (CDN) â†’ https://www.chartjs.org/

CARA MENGUBAH WARNA CHART:
   - Grafik garis  : cari 'borderColor' di chart 'consultationTrendChart'
   - Grafik donat  : cari 'backgroundColor' di chart 'consultationStatusChart'
   - Grafik batang : cari 'backgroundColor' di masing-masing dataset bar

CARA MENGUBAH JUDUL CHART:
   - Cari tag <h2> di bawah komentar masing-masing chart
   - Ubah teks di dalam tag tersebut

CARA MENGUBAH PERIODE DATA (BUKAN 6 BULAN):
   - Edit DashboardController@index â†’ ubah query yang mengirim $chartLabels
==========================================================================
--}}

{{-- ======================================================================
     BARIS 1: GRAFIK GARIS + GRAFIK DONAT (2 kolom)
     ====================================================================== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- GRAFIK GARIS: Tren Permintaan Konsultasi (lebar 2/3 layar) --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                {{-- UBAH JUDUL GRAFIK GARIS DI SINI --}}
                <h2 class="text-base sm:text-lg font-bold text-[#23364f]">Tren Permintaan Konsultasi</h2>
                {{-- UBAH SUBTITLE DI SINI --}}
                <p class="text-xs text-gray-500">Jumlah pengajuan klien dalam 6 bulan terakhir</p>
            </div>
            <div class="flex items-center gap-2">
                {{-- Label badge periode --}}
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-[#23364f]">
                    6 Bulan Terakhir
                </span>

                {{-- TOMBOL EXPORT EXCEL
                     Route: admin.dashboard.export.excel
                     Controller: DashboardController@exportExcel
                     File Excel: app/Exports/KonsultasiExport.php --}}
                <a href="{{ route('admin.dashboard.export.excel') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition">
                    <i class="fas fa-file-excel"></i>
                    <span>Export Excel</span>
                </a>
            </div>
        </div>

        {{-- Canvas untuk grafik garis â€” diisi oleh Chart.js via id="consultationTrendChart" --}}
        <div class="relative h-64 sm:h-72 w-full">
            <canvas id="consultationTrendChart"></canvas>
        </div>
    </div>

    {{-- GRAFIK DONAT: Distribusi Status (lebar 1/3 layar) --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
        <div class="mb-4">
            {{-- UBAH JUDUL GRAFIK DONAT DI SINI --}}
            <h2 class="text-base sm:text-lg font-bold text-[#23364f]">Distribusi Status</h2>
            <p class="text-xs text-gray-500">Proporsi status seluruh permintaan</p>
        </div>

        {{-- Canvas grafik donat â€” id="consultationStatusChart"
             Jika data kosong, tampilkan pesan "statusChartEmpty" --}}
        <div class="relative h-56 w-full flex items-center justify-center">
            <canvas id="consultationStatusChart"></canvas>
            <div id="statusChartEmpty" class="hidden text-center text-sm text-gray-400">
                Belum ada data status permintaan.
            </div>
        </div>
    </div>
</div>

{{-- ======================================================================
     BARIS 2: DIAGRAM BATANG â€” Perbandingan Status per Bulan (lebar penuh)
     ====================================================================== --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div>
            {{-- UBAH JUDUL GRAFIK BATANG DI SINI --}}
            <h2 class="text-base sm:text-lg font-bold text-[#23364f]">Perbandingan Status Konsultasi per Bulan</h2>
            <p class="text-xs text-gray-500">Diagram batang rincian status Menunggu, Diproses, dan Selesai dalam 6 bulan terakhir</p>
        </div>
        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-[#23364f]">
            Diagram Batang
        </span>
    </div>

    {{-- Canvas grafik batang â€” id="consultationStatusBarChart" --}}
    <div class="relative h-72 sm:h-80 w-full">
        <canvas id="consultationStatusBarChart"></canvas>
    </div>
</div>

{{-- ======================================================================
     JAVASCRIPT: Inisialisasi Chart.js
     Semua chart diinisialisasi setelah halaman (DOMContentLoaded) siap.
     Jika Chart.js belum dimuat (CDN lambat), tunggu 100ms lalu coba lagi.
     ====================================================================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /**
     * Fungsi inisialisasi chart.
     * Dipanggil ulang setiap 100ms jika Chart.js belum dimuat.
     */
    const initDashboardCharts = () => {
        if (typeof Chart === 'undefined') {
            setTimeout(initDashboardCharts, 100); // Tunggu CDN selesai dimuat
            return;
        }

        // â”€â”€â”€ Ambil data dari PHP/Blade ke JavaScript â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        // Data ini dikirim dari DashboardController@index via compact()
        const trendLabels = @json($chartLabels ?? []);       // Label bulan: ['Jan', 'Feb', ...]
        const trendData   = @json($chartTrendData ?? []);    // Jumlah permintaan per bulan
        const statusData  = @json($chartStatusData ?? ['menunggu' => 0, 'diproses' => 0, 'selesai' => 0]);
        const barMenunggu = @json($chartBarMenunggu ?? []); // Status menunggu per bulan
        const barDiproses = @json($chartBarDiproses ?? []); // Status diproses per bulan
        const barSelesai  = @json($chartBarSelesai ?? []);  // Status selesai per bulan

        // â”€â”€â”€ CHART 1: Grafik Garis â€” Tren Permintaan â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        // Untuk ubah tampilan: edit bagian 'datasets' di bawah
        const trendCanvas = document.getElementById('consultationTrendChart');
        if (trendCanvas) {
            const ctx = trendCanvas.getContext('2d');

            // Efek gradien di bawah garis
            const gradient = ctx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, 'rgba(35, 54, 79, 0.18)');
            gradient.addColorStop(1, 'rgba(35, 54, 79, 0.02)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Jumlah Permintaan',
                        data: trendData,
                        borderColor: '#23364f',        // â† UBAH WARNA GARIS DI SINI
                        backgroundColor: gradient,     // â† Gradien area bawah garis
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,                 // Kelengkungan garis (0 = lurus, 1 = sangat melengkung)
                        pointBackgroundColor: '#ffffff', // â† Warna titik data
                        pointBorderColor: '#23364f',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }, // Sembunyikan legenda
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, color: '#64748b' }, // Hanya angka bulat
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            ticks: { color: '#64748b' },
                            grid: { display: false } // Sembunyikan garis vertikal
                        }
                    }
                }
            });
        }

        // â”€â”€â”€ CHART 2: Grafik Donat â€” Distribusi Status â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        // Untuk ubah warna: edit 'backgroundColor' di bawah
        const statusCanvas = document.getElementById('consultationStatusChart');
        const emptyState   = document.getElementById('statusChartEmpty');
        if (statusCanvas) {
            const values = [
                Number(statusData.menunggu || 0), // Kuning
                Number(statusData.diproses || 0), // Biru
                Number(statusData.selesai  || 0)  // Hijau
            ];
            const totalStatus = values.reduce((acc, val) => acc + val, 0);

            // Jika tidak ada data, tampilkan pesan kosong
            if (totalStatus === 0 && emptyState) {
                statusCanvas.classList.add('hidden');
                emptyState.classList.remove('hidden');
            } else {
                new Chart(statusCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Menunggu', 'Diproses', 'Selesai'],
                        datasets: [{
                            data: values,
                            backgroundColor: ['#f59e0b', '#3b82f6', '#10b981'], // â† UBAH WARNA DONAT DI SINI
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%', // Ukuran lubang tengah (makin besar = makin tipis lingkaran)
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { usePointStyle: true, padding: 16, color: '#334155' }
                            }
                        }
                    }
                });
            }
        }

        // â”€â”€â”€ CHART 3: Diagram Batang â€” Perbandingan Status per Bulan â”€â”€â”€â”€â”€
        // Untuk ubah warna batang: edit 'backgroundColor' di masing-masing dataset
        const barCanvas = document.getElementById('consultationStatusBarChart');
        if (barCanvas) {
            new Chart(barCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: trendLabels, // Pakai label bulan yang sama dengan chart 1
                    datasets: [
                        {
                            label: 'Menunggu',
                            data: barMenunggu,
                            backgroundColor: '#f59e0b', // â† UBAH WARNA BATANG MENUNGGU
                            borderRadius: 6,
                            maxBarThickness: 28
                        },
                        {
                            label: 'Diproses',
                            data: barDiproses,
                            backgroundColor: '#3b82f6', // â† UBAH WARNA BATANG DIPROSES
                            borderRadius: 6,
                            maxBarThickness: 28
                        },
                        {
                            label: 'Selesai',
                            data: barSelesai,
                            backgroundColor: '#10b981', // â† UBAH WARNA BATANG SELESAI
                            borderRadius: 6,
                            maxBarThickness: 28
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false }, // Tooltip muncul untuk semua dataset di x yang sama
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: { usePointStyle: true, padding: 16, color: '#334155' }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, color: '#64748b' },
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            ticks: { color: '#64748b' },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    };

    initDashboardCharts();
});
</script>
