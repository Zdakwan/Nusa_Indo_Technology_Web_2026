<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Grafik Garis: Tren Permintaan Konsultasi --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-[#23364f]">Tren Permintaan Konsultasi</h2>
                <p class="text-xs text-gray-500">Jumlah pengajuan klien dalam 6 bulan terakhir</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-[#23364f]">
                    6 Bulan Terakhir
                </span>
                <a href="{{ route('admin.dashboard.export.excel') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition">
                    <i class="fas fa-file-excel"></i>
                    <span>Export Excel</span>
                </a>
            </div>
        </div>
        <div class="relative h-64 sm:h-72 w-full">
            <canvas id="consultationTrendChart"></canvas>
        </div>
    </div>

    {{-- Grafik Donat: Distribusi Status --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
        <div class="mb-4">
            <h2 class="text-base sm:text-lg font-bold text-[#23364f]">Distribusi Status</h2>
            <p class="text-xs text-gray-500">Proporsi status seluruh permintaan</p>
        </div>
        <div class="relative h-56 w-full flex items-center justify-center">
            <canvas id="consultationStatusChart"></canvas>
            <div id="statusChartEmpty" class="hidden text-center text-sm text-gray-400">
                Belum ada data status permintaan.
            </div>
        </div>
    </div>
</div>

{{-- Diagram Batang: Perbandingan Status Konsultasi per Bulan --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-[#23364f]">Perbandingan Status Konsultasi per Bulan</h2>
            <p class="text-xs text-gray-500">Diagram batang rincian status Menunggu, Diproses, dan Selesai dalam 6 bulan terakhir</p>
        </div>
        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-[#23364f]">
            Diagram Batang
        </span>
    </div>
    <div class="relative h-72 sm:h-80 w-full">
        <canvas id="consultationStatusBarChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const initDashboardCharts = () => {
        if (typeof Chart === 'undefined') {
            setTimeout(initDashboardCharts, 100);
            return;
        }

        const trendLabels = @json($chartLabels ?? []);
        const trendData   = @json($chartTrendData ?? []);
        const statusData  = @json($chartStatusData ?? ['menunggu' => 0, 'diproses' => 0, 'selesai' => 0]);
        const barMenunggu = @json($chartBarMenunggu ?? []);
        const barDiproses = @json($chartBarDiproses ?? []);
        const barSelesai  = @json($chartBarSelesai ?? []);

        // 1. Line Chart: Tren Permintaan Konsultasi
        const trendCanvas = document.getElementById('consultationTrendChart');
        if (trendCanvas) {
            const ctx = trendCanvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, 'rgba(35, 54, 79, 0.22)');
            gradient.addColorStop(1, 'rgba(35, 54, 79, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Jumlah Permintaan',
                        data: trendData,
                        borderColor: '#1b314c',
                        backgroundColor: gradient,
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#1b314c',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
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

        // 2. Doughnut Chart: Distribusi Status
        const statusCanvas = document.getElementById('consultationStatusChart');
        const emptyState   = document.getElementById('statusChartEmpty');
        if (statusCanvas) {
            const values = [
                Number(statusData.menunggu || 0),
                Number(statusData.diproses || 0),
                Number(statusData.selesai || 0)
            ];
            const totalStatus = values.reduce((acc, val) => acc + val, 0);

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
                            backgroundColor: ['#f59e0b', '#3b82f6', '#10b981'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
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

        // 3. Bar Chart: Perbandingan Status Konsultasi per Bulan
        const barCanvas = document.getElementById('consultationStatusBarChart');
        if (barCanvas) {
            new Chart(barCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: trendLabels,
                    datasets: [
                        {
                            label: 'Menunggu',
                            data: barMenunggu,
                            backgroundColor: '#f59e0b',
                            borderRadius: 6,
                            maxBarThickness: 28
                        },
                        {
                            label: 'Diproses',
                            data: barDiproses,
                            backgroundColor: '#3b82f6',
                            borderRadius: 6,
                            maxBarThickness: 28
                        },
                        {
                            label: 'Selesai',
                            data: barSelesai,
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                            maxBarThickness: 28
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
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