{{-- Grafik Analitik Dashboard Admin --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 my-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-[#23364f]">Tren Permintaan Konsultasi</h2>
                <p class="text-xs text-gray-500">Jumlah pengajuan klien dalam 6 bulan terakhir</p>
            </div>
<<<<<<< HEAD
            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-[#23364f]">
                6 Bulan Terakhir
            </span>
=======
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
>>>>>>> mey
        </div>
        <div class="relative h-64 sm:h-72 w-full">
            <canvas id="consultationTrendChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 flex flex-col justify-between">
        <div class="mb-4">
            <h2 class="text-base sm:text-lg font-bold text-[#23364f]">Distribusi Status</h2>
            <p class="text-xs text-gray-500">Proporsi status seluruh permintaan</p>
        </div>
        <div class="relative h-56 sm:h-64 w-full flex items-center justify-center">
            <canvas id="consultationStatusChart"></canvas>
            <div id="statusChartEmpty" class="hidden text-center text-sm text-gray-400">
                Belum ada data konsultasi
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof Chart === 'undefined') return;

    const trendLabels = @json($chartLabels ?? []);
    const trendData   = @json($chartTrendData ?? []);
    const statusData  = @json($chartStatusData ?? ['menunggu' => 0, 'diproses' => 0, 'selesai' => 0]);

    const trendCanvas = document.getElementById('consultationTrendChart');
    if (trendCanvas) {
        const ctx = trendCanvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(35, 54, 79, 0.25)');
        gradient.addColorStop(1, 'rgba(35, 54, 79, 0.01)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Permintaan Konsultasi',
                    data: trendData,
                    borderColor: '#23364f',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#f5b73e',
                    pointBorderColor: '#23364f',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#0f172a', padding: 10, cornerRadius: 8 }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0, color: '#64748b' }, grid: { color: '#f1f5f9' } },
                    x: { ticks: { color: '#64748b' }, grid: { display: false } }
                }
            }
        });
    }

    const statusCanvas = document.getElementById('consultationStatusChart');
    const emptyState   = document.getElementById('statusChartEmpty');
    const statusValues = [
        Number(statusData.menunggu || 0),
        Number(statusData.diproses || 0),
        Number(statusData.selesai || 0)
    ];

    if (statusCanvas) {
        if (statusValues.reduce((a, b) => a + b, 0) === 0) {
            statusCanvas.classList.add('hidden');
            emptyState?.classList.remove('hidden');
            return;
        }

        new Chart(statusCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Menunggu', 'Diproses', 'Selesai'],
                datasets: [{
                    data: statusValues,
                    backgroundColor: ['#f5b73e', '#3b82f6', '#10b981'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 16, color: '#334155', font: { size: 12, weight: '600' } }
                    }
                }
            }
        });
    }
});
</script>