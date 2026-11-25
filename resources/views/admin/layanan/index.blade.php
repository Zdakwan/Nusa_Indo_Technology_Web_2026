@extends('layouts.admin')

@section('title', 'Manajemen Layanan')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Layanan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data layanan IT Nusa Indo Tech.</p>
    </div>
    <div class="flex items-center gap-2">
        <!-- Tombol Toggle Mode Pilih Hapus -->
        <button type="button" id="btnToggleBulk" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
            <i class="fa-solid fa-list-check"></i> Pilih Hapus
        </button>

<<<<<<< HEAD
        <!-- Tombol Hapus Terpilih (Sembunyi secara default) -->
        <button type="submit" form="bulkDeleteForm" id="btnBulkDelete" disabled class="hidden inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg shadow-sm disabled:opacity-50 disabled:cursor-not-allowed transition-all" onclick="return confirm('Apakah Anda yakin ingin menghapus data layanan yang dipilih?')">
            <i class="fa-solid fa-trash-alt"></i> Hapus Terpilih
        </button>

        <button type="button" onclick="openModal('tambahModal')" class="inline-flex items-center gap-2 px-4 py-2 bg-[#254261] hover:bg-[#1a2f45] text-white text-sm font-medium rounded-lg shadow-sm">
            <i class="fa-solid fa-plus"></i> Tambah Layanan
        </button>
    </div>
=======
  <button type="button" onclick="openModal('tambahModal')" class="inline-flex items-center gap-2 px-4 py-2 bg-[#254261] hover:bg-[#1a2f45] text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
>>>>>>> mey
</div>

@if(session('success'))
    <div class="mb-6 px-4 py-3 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> <span class="font-medium">{{ session('success') }}</span>
        </div>
<<<<<<< HEAD
        <button type="button" class="hover:text-green-900" onclick="this.parentElement.style.display='none'"><i class="fa-solid fa-times"></i></button>
=======
     <button type="button" class="text-green-700 hover:text-green-900" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times"></i>
        </button>
>>>>>>> mey
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 px-4 py-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm" role="alert">
        <div class="flex items-center gap-2 mb-2">
            <i class="fa-solid fa-exclamation-circle"></i><span class="font-bold">Gagal menyimpan data!</span>
        </div>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<!-- Form untuk Bulk Delete membungkus tabel -->
<form id="bulkDeleteForm" action="{{ route('admin.layanan.bulkDelete') }}" method="POST">
    @csrf
    @method('DELETE')

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <!-- Header Checkbox (Sembunyi secara default) -->
                        <th class="bulk-action-cell hidden px-6 py-4 text-sm font-semibold text-gray-600 w-12 text-center">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-[#254261] focus:ring-[#254261] cursor-pointer">
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 w-16 text-center">No</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 w-24 text-center">Icon</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600">Nama Layanan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600">Kategori</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600">Deskripsi</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($layanan as $key => $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- Baris Checkbox (Sembunyi secara default) -->
                        <td class="bulk-action-cell hidden px-6 py-4 text-center">
                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="rowCheckbox rounded border-gray-300 text-[#254261] focus:ring-[#254261] cursor-pointer">
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 text-center">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mx-auto text-lg">
                                <i class="{{ $item->icon ?? 'fa-solid fa-gears' }}"></i>
                            </div>
<<<<<<< HEAD
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800 font-bold">{{ $item->nama_layanan }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <span class="px-2 py-1 bg-gray-100 rounded text-xs font-semibold tracking-wide uppercase">{{ $item->kategori }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-xs">{{ Str::limit($item->deskripsi, 50) }}</td>
                        <td class="px-6 py-4 text-center space-x-2 whitespace-nowrap">
                            <button type="button"
                                onclick="openEditModal(this)"
                                data-id="{{ $item->id }}"
                                data-nama="{{ $item->nama_layanan }}"
                                data-kategori="{{ $item->kategori }}"
                                data-icon="{{ $item->icon }}"
                                data-deskripsi="{{ $item->deskripsi }}"
                                class="text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                                <i class="fa-solid fa-edit text-lg"></i>
=======
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800 font-bold">{{ $item->nama_layanan }}</td>

                    <td class="px-6 py-4">
                        <span class="inline-block px-2.5 py-1 text-[11px] font-bold tracking-wider uppercase bg-[#254261]/10 text-[#254261] rounded">
                            {{ $item->kategori ?? 'Umum' }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($item->deskripsi, 80) }}</td>
                    <td class="px-6 py-4 text-center space-x-2 whitespace-nowrap">
                            <button 
                        data-id="{{ $item->id }}"
                        data-nama="{{ $item->nama_layanan }}"
                        data-kategori="{{ $item->kategori ?? 'Umum' }}"
                        data-deskripsi="{{ $item->deskripsi }}"
                        onclick="openEditModal(this.dataset.id, this.dataset.nama, this.dataset.kategori, this.dataset.deskripsi)" 
                        class="text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                        <i class="fas fa-edit text-lg"></i>
                    </button>
                        <form action="{{ route('admin.layanan.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus layanan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                <i class="fas fa-trash-alt text-lg"></i>
>>>>>>> mey
                            </button>
                            <button type="button" onclick="deleteSingle('{{ $item->id }}')" class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                <i class="fa-solid fa-trash-alt text-lg"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">Belum ada data layanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</form>

<!-- Form Hapus Satuan (Hidden) -->
<form id="singleDeleteForm" method="POST" class="hidden">
    @csrf @method('DELETE')
</form>

@php
    $iconList = [
        'fa-solid fa-laptop-code', 'fa-solid fa-folder-open', 'fa-solid fa-building-columns',
        'fa-solid fa-circle-play', 'fa-solid fa-microchip', 'fa-solid fa-code',
        'fa-solid fa-shield-halved', 'fa-solid fa-network-wired', 'fa-solid fa-server',
        'fa-solid fa-cloud', 'fa-solid fa-chart-pie', 'fa-solid fa-gears',
        'fa-solid fa-video', 'fa-solid fa-camera', 'fa-solid fa-headset',
        'fa-solid fa-database', 'fa-solid fa-wifi', 'fa-solid fa-fingerprint',
        'fa-solid fa-chalkboard-user', 'fa-solid fa-mobile-screen'
    ];
@endphp

<!-- Modal Tambah -->
<<<<<<< HEAD
<div id="tambahModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg">
        <div class="flex justify-between p-4 border-b">
            <h3 class="text-xl font-semibold">Tambah Layanan</h3>
            <button type="button" onclick="closeModal('tambahModal')"><i class="fa-solid fa-times text-gray-500 hover:text-gray-800"></i></button>
=======
<div id="tambahModal" tabindex="-1" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center">
    <div class="relative p-4 w-full max-w-xl">
        <div class="relative bg-white rounded-xl shadow-lg">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Tambah Layanan</h3>
                <button type="button" onclick="closeModal('tambahModal')" class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('admin.layanan.store') }}" method="POST" enctype="multipart/form-data" class="p-4">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Nama Layanan</label>
                        <input type="text" name="nama_layanan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#254261] focus:border-[#254261] block w-full p-2.5" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                        <select name="kategori" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#254261] focus:border-[#254261] block w-full p-2.5" required>
                            <option value="Umum">Umum</option>
                            <option value="Tata Kelola">Tata Kelola</option>
                            <option value="Development">Development</option>
                            <option value="Infrastruktur">Infrastruktur</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-[#254261] focus:border-[#254261]" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Icon / Gambar</label>
                    <!-- Tambah atribut accept di sini -->
                    <input type="file" name="icon" accept=".jpg,.jpeg,.png,.gif" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-[#254261] file:text-white hover:file:bg-[#1a2f45]">
                </div>
                <div class="flex justify-end gap-3 mt-6 border-t pt-4">
                    <button type="button" onclick="closeModal('tambahModal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#254261] rounded-lg hover:bg-[#1a2f45]">Simpan</button>
                </div>
            </form>
>>>>>>> mey
        </div>
        <form action="{{ route('admin.layanan.store') }}" method="POST" class="p-4">
            @csrf
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Layanan</label>
                    <input type="text" name="nama_layanan" value="{{ old('nama_layanan') }}" class="w-full border rounded-lg p-2 focus:ring-[#254261] focus:border-[#254261]" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Kategori</label>
                    <select name="kategori" class="w-full border rounded-lg p-2 focus:ring-[#254261] focus:border-[#254261]" required>
                        <option value="DEVELOPMENT" {{ old('kategori') == 'DEVELOPMENT' ? 'selected' : '' }}>DEVELOPMENT</option>
                        <option value="TATA KELOLA" {{ old('kategori') == 'TATA KELOLA' ? 'selected' : '' }}>TATA KELOLA</option>
                        <option value="UMUM" {{ old('kategori') == 'UMUM' ? 'selected' : '' }}>UMUM</option>
                    </select>
                </div>
            </div>

            <!-- Visual Icon Picker (Tambah) -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Pilih Icon Layanan</label>
                <input type="hidden" name="icon" id="tambah_icon_input" value="fa-solid fa-laptop-code" required>

                <div class="flex items-center gap-3 mb-3">
                    <div id="tambah_icon_preview" class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl border border-blue-100">
                        <i class="fa-solid fa-laptop-code"></i>
                    </div>
                    <span class="text-sm text-gray-500">Icon terpilih</span>
                </div>

                <div class="grid grid-cols-5 gap-2 max-h-32 overflow-y-auto p-2 border rounded-lg bg-gray-50">
                    @foreach($iconList as $icon)
                        <button type="button" onclick="selectIcon('tambah', '{{ $icon }}')" class="p-2 rounded-lg hover:bg-blue-100 text-gray-600 hover:text-blue-600 transition flex justify-center items-center text-lg focus:outline-none">
                            <i class="{{ $icon }}"></i>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full border rounded-lg p-2 focus:ring-[#254261] focus:border-[#254261]" required>{{ old('deskripsi') }}</textarea>
            </div>
            <div class="flex justify-end gap-2 border-t pt-4">
                <button type="button" onclick="closeModal('tambahModal')" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-[#254261] text-white rounded-lg hover:bg-[#1a2f45]">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<<<<<<< HEAD
<div id="editModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg">
        <div class="flex justify-between p-4 border-b">
            <h3 class="text-xl font-semibold">Edit Layanan</h3>
            <button type="button" onclick="closeModal('editModal')"><i class="fa-solid fa-times text-gray-500 hover:text-gray-800"></i></button>
=======
<div id="editModal" tabindex="-1" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center">
    <div class="relative p-4 w-full max-w-xl">
        <div class="relative bg-white rounded-xl shadow-lg">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Edit Layanan</h3>
                <button type="button" onclick="closeModal('editModal')" class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center"><i class="fas fa-times"></i></button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Nama Layanan</label>
                        <input type="text" name="nama_layanan" id="edit_nama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                        <select name="kategori" id="edit_kategori" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                            <option value="Umum">Umum</option>
                            <option value="Tata Kelola">Tata Kelola</option>
                            <option value="Development">Development</option>
                            <option value="Infrastruktur">Infrastruktur</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Icon / Gambar Baru (Opsional)</label>
                    <!-- Tambah atribut accept di sini -->
                    <input type="file" name="icon" accept=".jpg,.jpeg,.png,.gif" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-[#254261] file:text-white">
                </div>
                <div class="flex justify-end gap-3 mt-6 border-t pt-4">
                    <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-[#254261] rounded-lg hover:bg-[#1a2f45]">Update</button>
                </div>
            </form>
>>>>>>> mey
        </div>
        <form id="editForm" method="POST" class="p-4">
            @csrf @method('PUT')
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Layanan</label>
                    <input type="text" id="edit_nama" name="nama_layanan" class="w-full border rounded-lg p-2 focus:ring-[#254261] focus:border-[#254261]" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Kategori</label>
                    <select id="edit_kategori" name="kategori" class="w-full border rounded-lg p-2 focus:ring-[#254261] focus:border-[#254261]" required>
                        <option value="DEVELOPMENT">DEVELOPMENT</option>
                        <option value="TATA KELOLA">TATA KELOLA</option>
                        <option value="UMUM">UMUM</option>
                    </select>
                </div>
            </div>

            <!-- Visual Icon Picker (Edit) -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Pilih Icon Layanan</label>
                <input type="hidden" name="icon" id="edit_icon_input" required>

                <div class="flex items-center gap-3 mb-3">
                    <div id="edit_icon_preview" class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl border border-blue-100">
                        <i class="fa-solid fa-gears"></i>
                    </div>
                    <span class="text-sm text-gray-500">Icon terpilih</span>
                </div>

                <div class="grid grid-cols-5 gap-2 max-h-32 overflow-y-auto p-2 border rounded-lg bg-gray-50">
                    @foreach($iconList as $icon)
                        <button type="button" onclick="selectIcon('edit', '{{ $icon }}')" class="p-2 rounded-lg hover:bg-blue-100 text-gray-600 hover:text-blue-600 transition flex justify-center items-center text-lg focus:outline-none">
                            <i class="{{ $icon }}"></i>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea id="edit_deskripsi" name="deskripsi" rows="3" class="w-full border rounded-lg p-2 focus:ring-[#254261] focus:border-[#254261]" required></textarea>
            </div>
            <div class="flex justify-end gap-2 border-t pt-4">
                <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-[#254261] text-white rounded-lg hover:bg-[#1a2f45]">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi Manajemen Modal
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

<<<<<<< HEAD
    function selectIcon(modalPrefix, iconClass) {
        document.getElementById(modalPrefix + '_icon_input').value = iconClass;
        document.getElementById(modalPrefix + '_icon_preview').innerHTML = '<i class="' + iconClass + '"></i>';
    }

    function openEditModal(btn) {
        document.getElementById('edit_nama').value = btn.getAttribute('data-nama');
        document.getElementById('edit_kategori').value = btn.getAttribute('data-kategori');
        document.getElementById('edit_deskripsi').value = btn.getAttribute('data-deskripsi');

        let iconClass = btn.getAttribute('data-icon') || 'fa-solid fa-gears';
        selectIcon('edit', iconClass);

        document.getElementById('editForm').action = '/admin/layanan/' + btn.getAttribute('data-id');
=======
    function openEditModal(id, nama, kategori, deskripsi) {
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_kategori').value = kategori;
        document.getElementById('edit_deskripsi').value = deskripsi;
        document.getElementById('editForm').action = '/admin/layanan/' + id;
>>>>>>> mey
        openModal('editModal');
    }

    function deleteSingle(id) {
        if(confirm('Hapus layanan ini?')) {
            let form = document.getElementById('singleDeleteForm');
            form.action = '/admin/layanan/' + id;
            form.submit();
        }
    }

    // Script Mode Pilih Hapus & Checkbox
    document.addEventListener('DOMContentLoaded', function () {
        const btnToggleBulk = document.getElementById('btnToggleBulk');
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.rowCheckbox');
        const btnBulkDelete = document.getElementById('btnBulkDelete');
        const bulkCells = document.querySelectorAll('.bulk-action-cell');

        let isBulkMode = false;

        // Toggle Mode
        if (btnToggleBulk) {
            btnToggleBulk.addEventListener('click', function() {
                isBulkMode = !isBulkMode;

                if (isBulkMode) {
                    // Masuk mode hapus
                    bulkCells.forEach(cell => cell.classList.remove('hidden'));
                    btnBulkDelete.classList.remove('hidden');

                    // Ubah tombol toggle menjadi batal
                    this.innerHTML = '<i class="fa-solid fa-times"></i> Batal Pilih';
                    this.classList.replace('bg-gray-500', 'bg-gray-400');
                    this.classList.replace('hover:bg-gray-600', 'hover:bg-gray-500');
                } else {
                    // Keluar mode hapus
                    bulkCells.forEach(cell => cell.classList.add('hidden'));
                    btnBulkDelete.classList.add('hidden');

                    // Reset checkbox
                    if(selectAll) selectAll.checked = false;
                    checkboxes.forEach(cb => cb.checked = false);
                    toggleBulkDeleteBtn();

                    // Kembalikan tombol toggle
                    this.innerHTML = '<i class="fa-solid fa-list-check"></i> Pilih Hapus';
                    this.classList.replace('bg-gray-400', 'bg-gray-500');
                    this.classList.replace('hover:bg-gray-500', 'hover:bg-gray-600');
                }
            });
        }

        // Logic disable/enable tombol Hapus Terpilih
        const toggleBulkDeleteBtn = () => {
            const checkedCount = document.querySelectorAll('.rowCheckbox:checked').length;
            btnBulkDelete.disabled = checkedCount === 0;
        };

        if(selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = this.checked);
                toggleBulkDeleteBtn();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                if (!this.checked) selectAll.checked = false;
                toggleBulkDeleteBtn();
            });
        });
    });

    @if ($errors->any() && !old('_method'))
        openModal('tambahModal');
    @endif
</script>
<<<<<<< HEAD
@endsection
=======
@endpush
@endsection
>>>>>>> mey
