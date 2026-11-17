@extends('layouts.admin')

@section('title', 'Manajemen Proyek')

@section('content')
<div class="space-y-6">

    {{-- HEADER HALAMAN --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Manajemen Proyek
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Kelola proyek client dan pantau perkembangan pengerjaannya.
            </p>
        </div>

        <div class="flex items-center gap-2">

            <!-- Tombol Toggle Mode Pilih Hapus -->
            <button type="button" id="btnToggleBulk" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                <i class="fa-solid fa-list-check"></i> Pilih Hapus
            </button>

            <!-- Tombol Hapus Terpilih (Sembunyi secara default) -->
            <button type="submit" form="bulkDeleteForm" id="btnBulkDelete" disabled class="hidden inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg shadow-sm disabled:opacity-50 disabled:cursor-not-allowed transition-all" onclick="return confirm('Apakah Anda yakin ingin menghapus proyek yang dipilih?')">
                <i class="fa-solid fa-trash-alt"></i> Hapus Terpilih
            </button>

            <!-- Tombol Tambah Proyek -->
            <a href="{{ route('admin.proyek.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-lg bg-[#1e3352] text-white text-sm font-semibold hover:bg-[#2a4165] transition shadow-sm">
                <i class="fas fa-plus"></i>
                Tambah Proyek
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI SUKSES --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
            <i class="fas fa-check-circle"></i>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif

    {{-- NOTIFIKASI ERROR --}}
    @if($errors->any())
        <div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
            <p class="font-semibold text-sm mb-2">
                Terdapat kesalahan:
            </p>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TABEL PROYEK --}}
    <form id="bulkDeleteForm" action="{{ route('admin.proyek.bulkDelete') }}" method="POST">
        @csrf
        @method('DELETE')

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="p-5 border-b border-gray-200">
            <h2 class="font-semibold text-gray-800">
                Daftar Proyek Client
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Seluruh proyek yang telah dibuat oleh admin.
            </p>
        </div>

        @if($proyek->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <!-- Header Checkbox (Sembunyi secara default) -->
                            <th class="bulk-action-cell hidden px-6 py-4 w-12 text-center">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-[#254261] focus:ring-[#254261] cursor-pointer">
                            </th>
                            <th class="px-6 py-4">No.</th>
                            <th class="px-6 py-4">Nama Proyek</th>
                            <th class="px-6 py-4">Client</th>
                            <th class="px-6 py-4">Progres</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach($proyek as $index => $item)
                            <tr class="hover:bg-gray-50 transition">

                                <!-- Baris Checkbox (Sembunyi secara default) -->
                                <td class="bulk-action-cell hidden px-6 py-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="rowCheckbox rounded border-gray-300 text-[#254261] focus:ring-[#254261] cursor-pointer">
                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    {{ $proyek->firstItem() + $index }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">
                                        {{ $item->nama_proyek }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Dibuat {{ $item->created_at->format('d M Y') }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-700">
                                        {{ $item->client->name ?? 'Client tidak ditemukan' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 min-w-48">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-gray-600">
                                            Progress
                                        </span>
                                        <span class="text-xs font-bold text-[#1e3352]">
                                            {{ $item->progres }}%
                                        </span>
                                    </div>

                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div
                                            class="bg-[#f7bd55] h-2 rounded-full transition-all"
                                            style="width: {{ $item->progres }}%">
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($item->status === 'selesai')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                            Berjalan
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">

                                        <a href="{{ route('admin.proyek.show', $item->id) }}"
                                           title="Detail Proyek"
                                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.proyek.edit', $item->id) }}"
                                           title="Edit Proyek"
                                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button type="button"
                                                onclick="deleteSingleProyek('{{ $item->id }}')"
                                                title="Hapus Proyek"
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="p-5 border-t border-gray-200">
                {{ $proyek->links() }}
            </div>

        @else
            <div class="py-16 px-6 text-center">
                <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <i class="fas fa-folder-open text-2xl text-gray-400"></i>
                </div>

                <h3 class="font-semibold text-gray-700">
                    Belum Ada Proyek
                </h3>

                <p class="text-sm text-gray-500 mt-2">
                    Silakan tambahkan proyek pertama untuk client.
                </p>

                <a href="{{ route('admin.proyek.create') }}"
                   class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 rounded-lg bg-[#1e3352] text-white text-sm font-semibold hover:bg-[#2a4165]">
                    <i class="fas fa-plus"></i>
                    Tambah Proyek
                </a>
            </div>
        @endif

    </div>
    </form>

    <!-- Form Hapus Satuan (Hidden) -->
    <form id="singleDeleteProyekForm" method="POST" class="hidden">
        @csrf @method('DELETE')
    </form>
</div>

<script>
    function deleteSingleProyek(id) {
        if (confirm('Yakin ingin menghapus proyek ini? Seluruh riwayat progresnya juga akan terhapus.')) {
            let form = document.getElementById('singleDeleteProyekForm');
            form.action = '/admin/proyek/' + id;
            form.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const btnToggleBulk = document.getElementById('btnToggleBulk');
        const selectAll     = document.getElementById('selectAll');
        const checkboxes    = document.querySelectorAll('.rowCheckbox');
        const btnBulkDelete = document.getElementById('btnBulkDelete');
        const bulkCells     = document.querySelectorAll('.bulk-action-cell');

        let isBulkMode = false;

        const toggleBulkDeleteBtn = () => {
            const checkedCount = document.querySelectorAll('.rowCheckbox:checked').length;
            btnBulkDelete.disabled = checkedCount === 0;
        };

        if (btnToggleBulk) {
            btnToggleBulk.addEventListener('click', function () {
                isBulkMode = !isBulkMode;

                if (isBulkMode) {
                    bulkCells.forEach(cell => cell.classList.remove('hidden'));
                    btnBulkDelete.classList.remove('hidden');
                    this.innerHTML = '<i class="fa-solid fa-times"></i> Batal Pilih';
                    this.classList.replace('bg-gray-500', 'bg-gray-400');
                    this.classList.replace('hover:bg-gray-600', 'hover:bg-gray-500');
                } else {
                    bulkCells.forEach(cell => cell.classList.add('hidden'));
                    btnBulkDelete.classList.add('hidden');
                    if (selectAll) selectAll.checked = false;
                    checkboxes.forEach(cb => cb.checked = false);
                    toggleBulkDeleteBtn();
                    this.innerHTML = '<i class="fa-solid fa-list-check"></i> Pilih Hapus';
                    this.classList.replace('bg-gray-400', 'bg-gray-500');
                    this.classList.replace('hover:bg-gray-500', 'hover:bg-gray-600');
                }
            });
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = this.checked);
                toggleBulkDeleteBtn();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                if (!this.checked && selectAll) selectAll.checked = false;
                toggleBulkDeleteBtn();
            });
        });
    });
</script>
@endsection
