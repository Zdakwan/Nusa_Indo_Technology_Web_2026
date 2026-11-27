@extends('layouts.admin')

@section('title', 'Kelola Tentang Kami')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<style>
    .cropper-container { max-width: 100%; }
</style>
@endpush

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kelola Tentang Kami</h1>
        <p class="text-sm text-gray-500 mt-1">Atur penjelasan, visi misi, kontak, slider kolaborasi, dan struktur tim di satu tempat.</p>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 px-4 py-3 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i> <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button type="button" class="hover:text-green-900" onclick="this.parentElement.style.display='none'"><i class="fas fa-times"></i></button>
    </div>
@endif

<!-- Jika Ada Error (General) -->
@if ($errors->any())
    <div class="mb-6 px-4 py-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm" role="alert">
        <div class="flex items-center gap-2 mb-2">
            <i class="fas fa-exclamation-circle"></i>
            <span class="font-bold">Gagal menyimpan data!</span>
        </div>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- BUNGKUSAN FLEX UNTUK MENGATUR URUTAN (ORDER) -->
<div class="flex flex-col">

    <!-- FORM UTAMA: PROFIL & KOLABORASI (Class 'contents' agar tidak merusak urutan flex) -->
    <form action="{{ route('admin.tentang-kami.profil.update') }}" method="POST" enctype="multipart/form-data" class="contents">
        @csrf @method('PUT')

        <!-- BAGIAN 1: PROFIL, VISI MISI & KONTAK (Tampil paling atas - order-1) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 order-1">
            <div class="flex items-center justify-between mb-6 border-b pb-3">
                <h2 class="text-lg font-bold text-[#254261]"><i class="fas fa-building mr-2"></i> Informasi & Profil Perusahaan</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-6">
                <!-- Kolom Kiri -->
                <div class="space-y-5">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <h3 class="font-semibold text-gray-700 mb-4 border-b pb-2">Teks Pendahuluan (Hero)</h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Judul Utama</label>
                            <input type="text" name="judul" value="{{ old('judul', $tentang->judul ?? '') }}" class="w-full border rounded-lg p-2.5 bg-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Deskripsi Singkat</label>
                            <textarea name="deskripsi" rows="4" class="w-full border rounded-lg p-2.5 bg-white" required>{{ old('deskripsi', $tentang->deskripsi ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <h3 class="font-semibold text-gray-700 mb-4 border-b pb-2">Visi & Misi</h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Visi Perusahaan</label>
                            <textarea name="visi" rows="3" class="w-full border rounded-lg p-2.5 bg-white">{{ old('visi', $tentang->visi ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Misi Perusahaan</label>
                            <textarea name="misi" rows="4" class="w-full border rounded-lg p-2.5 bg-white">{{ old('misi', $tentang->misi ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-5">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <h3 class="font-semibold text-gray-700 mb-4 border-b pb-2">Informasi Kontak & Lokasi</h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="2" class="w-full border rounded-lg p-2.5 bg-white">{{ old('alamat', $tentang->alamat ?? '') }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Nomor Telepon</label>
                                <input type="text" name="telepon" value="{{ old('telepon', $tentang->telepon ?? '') }}" class="w-full border rounded-lg p-2.5 bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $tentang->email ?? '') }}" class="w-full border rounded-lg p-2.5 bg-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Link Embed Google Maps (src)</label>
                            <textarea name="gmaps_embed" rows="4" class="w-full border rounded-lg p-2.5 bg-white font-mono text-sm" placeholder="https://www.google.com/maps/embed?...">{{ old('gmaps_embed', $tentang->gmaps_embed ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 3: PENGATURAN SLIDER KOLABORASI (Tampil paling bawah - order-3) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 order-3">
            <div class="flex items-center justify-between mb-6 border-b pb-3">
                <h2 class="text-lg font-bold text-[#254261]"><i class="fas fa-handshake mr-2"></i> Pengaturan Slider Kolaborasi Pengembangan</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-6">
                <!-- Slide 1: Tim Pengembang -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <h3 class="font-semibold text-gray-700 mb-4 border-b pb-2">Slide 1: Tim Pengembang</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Foto Tim Mahasiswa Polije PSDKU Sidoarjo</label>
                        @php $hasFotoTim = isset($tentang->foto_kolaborasi_tim) && $tentang->foto_kolaborasi_tim; @endphp
                        <div id="preview-wrapper-tim" class="mb-3 {{ $hasFotoTim ? '' : 'hidden' }}">
                            <img id="preview-foto-tim"
                                 src="{{ $hasFotoTim ? (str_starts_with($tentang->foto_kolaborasi_tim, 'data:') ? $tentang->foto_kolaborasi_tim : asset('storage/' . $tentang->foto_kolaborasi_tim)) : '' }}"
                                 alt="Foto Tim"
                                 class="h-36 w-full max-w-xs object-cover rounded-lg border border-gray-200 shadow-sm">
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <input type="file"
                                   id="input-foto-tim"
                                   name="foto_kolaborasi_tim"
                                   accept="image/png,image/jpeg,image/jpg,image/gif,image/bmp"
                                   class="js-crop-input flex-1 min-w-0 border rounded-lg bg-white p-1.5 text-sm"
                                   data-preview-id="preview-foto-tim"
                                   data-wrapper-id="preview-wrapper-tim"
                                   data-recrop-id="btn-recrop-tim"
                                   data-aspect="1.3333"
                                   data-title="Sesuaikan Foto Tim Pengembang">
                            <button type="button" id="btn-recrop-tim" onclick="recropInput('input-foto-tim')" class="hidden px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition">
                                <i class="fas fa-crop-alt mr-1"></i> Crop Ulang
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-red-500 font-medium">*Ukuran maksimal 2 MB. Format: JPG, PNG, GIF, BMP. Foto bisa di-crop/dipaskan setelah dipilih.</p>
                        @error('foto_kolaborasi_tim')
                            <span class="text-red-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto saat ini.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Teks Penjelasan Tim</label>
                        <textarea name="teks_kolaborasi_tim" rows="4" class="w-full border rounded-lg p-2.5 bg-white">{{ old('teks_kolaborasi_tim', $tentang->teks_kolaborasi_tim ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Slide 2: Serah Terima -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <h3 class="font-semibold text-gray-700 mb-4 border-b pb-2">Slide 2: Serah Terima</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Foto Serah Terima Sistem</label>
                        @php $hasFotoSerahTerima = isset($tentang->foto_kolaborasi_serah_terima) && $tentang->foto_kolaborasi_serah_terima; @endphp
                        <div id="preview-wrapper-serah-terima" class="mb-3 {{ $hasFotoSerahTerima ? '' : 'hidden' }}">
                            <img id="preview-foto-serah-terima"
                                 src="{{ $hasFotoSerahTerima ? (str_starts_with($tentang->foto_kolaborasi_serah_terima, 'data:') ? $tentang->foto_kolaborasi_serah_terima : asset('storage/' . $tentang->foto_kolaborasi_serah_terima)) : '' }}"
                                 alt="Foto Serah Terima"
                                 class="h-36 w-full max-w-xs object-cover rounded-lg border border-gray-200 shadow-sm">
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <input type="file"
                                   id="input-foto-serah-terima"
                                   name="foto_kolaborasi_serah_terima"
                                   accept="image/png,image/jpeg,image/jpg,image/gif,image/bmp"
                                   class="js-crop-input flex-1 min-w-0 border rounded-lg bg-white p-1.5 text-sm"
                                   data-preview-id="preview-foto-serah-terima"
                                   data-wrapper-id="preview-wrapper-serah-terima"
                                   data-recrop-id="btn-recrop-serah-terima"
                                   data-aspect="1.3333"
                                   data-title="Sesuaikan Foto Serah Terima Sistem">
                            <button type="button" id="btn-recrop-serah-terima" onclick="recropInput('input-foto-serah-terima')" class="hidden px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition">
                                <i class="fas fa-crop-alt mr-1"></i> Crop Ulang
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-red-500 font-medium">*Ukuran maksimal 2 MB. Format: JPG, PNG, GIF, BMP. Foto bisa di-crop/dipaskan setelah dipilih.</p>
                        @error('foto_kolaborasi_serah_terima')
                            <span class="text-red-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto saat ini.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Teks Penjelasan Serah Terima</label>
                        <textarea name="teks_kolaborasi_serah_terima" rows="4" class="w-full border rounded-lg p-2.5 bg-white">{{ old('teks_kolaborasi_serah_terima', $tentang->teks_kolaborasi_serah_terima ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-[#254261] hover:bg-[#1a2f45] text-white font-bold rounded-lg shadow-sm">
                    <i class="fas fa-save mr-2"></i> Simpan Profil & Kolaborasi
                </button>
            </div>
        </div>
    </form>

    <!-- BAGIAN 2: MANAJEMEN TIM (Tampil di tengah - order-2) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8 order-2 relative z-10">
        <div class="p-6 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-lg font-bold text-[#254261]"><i class="fas fa-users mr-2"></i> @if(session('error') || $errors->any())
    <div id="nit-tim-error-alert" style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:0.875rem 1rem;border-radius:0.75rem;margin-bottom:1rem;font-size:0.875rem;">
        <strong>Gagal menyimpan data:</strong>
        <ul style="margin:0.25rem 0 0 1.25rem;list-style:disc;">
            @if(session('error'))
                <li>{{ session('error') }}</li>
            @endif
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
Struktur Tim Kami</h2>
            <button type="button" onclick="openModal('tambahModal')" class="inline-flex items-center gap-2 px-4 py-2 bg-[#f59e0b] hover:bg-amber-600 text-white text-sm font-bold rounded-lg">
                <i class="fas fa-plus"></i> Tambah Anggota
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 w-16 text-center">No</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600">Nama Lengkap</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600">Jabatan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-gray-600 w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tim as $key => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-600 text-center">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 font-bold">{{ $item->nama }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->jabatan }}</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <button type="button" onclick="openEditModal(this)" data-id="{{ $item->id }}" data-nama="{{ $item->nama }}" data-jabatan="{{ $item->jabatan }}" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.tentang-kami.tim.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada anggota tim.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- AKHIR DARI BUNGKUSAN FLEX -->

<!-- MODAL TAMBAH TIM -->
<div id="tambahModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4">
        <form action="{{ route('admin.tentang-kami.tim.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <h3 class="text-lg font-bold mb-4">Tambah Anggota Tim</h3>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border rounded-lg p-2.5 bg-gray-50" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jabatan</label>
                <input type="text" name="jabatan" value="{{ old('jabatan') }}" class="w-full border rounded-lg p-2.5 bg-gray-50" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Foto (Opsional)</label>
                <div id="preview-wrapper-tambah-tim" class="mb-2 hidden">
                    <img id="preview-foto-tambah-tim" src="" alt="Preview Foto Anggota" class="h-24 w-24 object-cover rounded-full border border-gray-200 shadow-sm">
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <input type="file"
                           id="input-foto-tambah-tim"
                           name="foto"
                           accept="image/png,image/jpeg,image/jpg,image/gif,image/bmp"
                           class="js-crop-input flex-1 min-w-0 border rounded-lg bg-gray-50 p-1.5 text-sm"
                           data-preview-id="preview-foto-tambah-tim"
                           data-wrapper-id="preview-wrapper-tambah-tim"
                           data-recrop-id="btn-recrop-tambah-tim"
                           data-aspect="1"
                           data-title="Sesuaikan Foto Anggota Tim">
                    <button type="button" id="btn-recrop-tambah-tim" onclick="recropInput('input-foto-tambah-tim')" class="hidden px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition">
                        <i class="fas fa-crop-alt mr-1"></i> Crop Ulang
                    </button>
                </div>
                <p class="mt-1 text-xs text-red-500 font-medium">*Ukuran maksimal 2 MB. Format: JPG, PNG, GIF, BMP.</p>
                @error('foto')
                    <span class="text-red-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t">
                <button type="button" onclick="closeModal('tambahModal')" class="px-4 py-2 border rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-[#f59e0b] text-white rounded-lg font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT TIM -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4">
        <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')
            <h3 class="text-lg font-bold mb-4">Edit Anggota Tim</h3>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                <input type="text" id="edit_nama" name="nama" class="w-full border rounded-lg p-2.5 bg-gray-50" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jabatan</label>
                <input type="text" id="edit_jabatan" name="jabatan" class="w-full border rounded-lg p-2.5 bg-gray-50" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Update Foto</label>
                <div id="preview-wrapper-edit-tim" class="mb-2 hidden">
                    <img id="preview-foto-edit-tim" src="" alt="Preview Foto Anggota" class="h-24 w-24 object-cover rounded-full border border-gray-200 shadow-sm">
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <input type="file"
                           id="input-foto-edit-tim"
                           name="foto"
                           accept="image/png,image/jpeg,image/jpg,image/gif,image/bmp"
                           class="js-crop-input flex-1 min-w-0 border rounded-lg bg-gray-50 p-1.5 text-sm"
                           data-preview-id="preview-foto-edit-tim"
                           data-wrapper-id="preview-wrapper-edit-tim"
                           data-recrop-id="btn-recrop-edit-tim"
                           data-aspect="1"
                           data-title="Sesuaikan Foto Anggota Tim">
                    <button type="button" id="btn-recrop-edit-tim" onclick="recropInput('input-foto-edit-tim')" class="hidden px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition">
                        <i class="fas fa-crop-alt mr-1"></i> Crop Ulang
                    </button>
                </div>
                <p class="mt-1 text-xs text-red-500 font-medium">*Ukuran maksimal 2 MB. Format: JPG, PNG, GIF, BMP.</p>
                @error('foto')
                    <span class="text-red-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t">
                <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 border rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-bold">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL CROP / PASKAN FOTO -->
<div id="cropImageModal" class="fixed inset-0 z-[60] hidden bg-gray-900/75 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-hidden flex flex-col max-h-[92vh]">
        <div class="px-5 py-4 border-b flex items-center justify-between bg-[#254261] text-white">
            <h3 id="cropModalTitle" class="text-base font-bold flex items-center gap-2">
                <i class="fas fa-crop-alt"></i> Sesuaikan / Crop Foto
            </h3>
            <button type="button" onclick="cancelCropModal()" class="text-white/80 hover:text-white text-lg">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-4 bg-gray-900 flex-1 overflow-hidden flex items-center justify-center min-h-[280px] max-h-[58vh]">
            <img id="cropperTargetImage" src="" alt="Preview Crop" class="max-w-full max-h-[54vh] block">
        </div>

        <div class="px-5 py-3 bg-gray-50 border-t flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-xs font-semibold text-gray-600 mr-1">Rasio:</span>
                <button type="button" onclick="setCropAspect(4/3)" class="crop-aspect-btn px-2.5 py-1 text-xs font-medium rounded border bg-white hover:bg-gray-100">4:3 (Slider)</button>
                <button type="button" onclick="setCropAspect(16/9)" class="crop-aspect-btn px-2.5 py-1 text-xs font-medium rounded border bg-white hover:bg-gray-100">16:9</button>
                <button type="button" onclick="setCropAspect(1)" class="crop-aspect-btn px-2.5 py-1 text-xs font-medium rounded border bg-white hover:bg-gray-100">1:1 (Tim)</button>
                <button type="button" onclick="setCropAspect(NaN)" class="crop-aspect-btn px-2.5 py-1 text-xs font-medium rounded border bg-white hover:bg-gray-100">Bebas</button>
            </div>

            <div class="flex items-center gap-1.5">
                <button type="button" onclick="cropperAction('zoom', 0.1)" title="Perbesar" class="px-2.5 py-1.5 text-xs bg-white border rounded hover:bg-gray-100"><i class="fas fa-search-plus"></i></button>
                <button type="button" onclick="cropperAction('zoom', -0.1)" title="Perkecil" class="px-2.5 py-1.5 text-xs bg-white border rounded hover:bg-gray-100"><i class="fas fa-search-minus"></i></button>
                <button type="button" onclick="cropperAction('rotate', -90)" title="Putar Kiri" class="px-2.5 py-1.5 text-xs bg-white border rounded hover:bg-gray-100"><i class="fas fa-undo"></i></button>
                <button type="button" onclick="cropperAction('rotate', 90)" title="Putar Kanan" class="px-2.5 py-1.5 text-xs bg-white border rounded hover:bg-gray-100"><i class="fas fa-redo"></i></button>
                <button type="button" onclick="cropperAction('reset')" title="Reset" class="px-2.5 py-1.5 text-xs bg-white border rounded hover:bg-gray-100">Reset</button>
            </div>
        </div>

        <div class="px-5 py-3 bg-white border-t flex items-center justify-between gap-2">
            <p class="text-xs text-gray-500">Geser atau zoom gambar untuk mempaskan area yang tampil.</p>
            <div class="flex items-center gap-2">
                <button type="button" onclick="cancelCropModal()" class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-50">Batal</button>
                <button type="button" onclick="applyCropImage()" class="px-5 py-2 text-sm bg-[#254261] hover:bg-[#1a2f45] text-white font-bold rounded-lg shadow-sm">
                    <i class="fas fa-check mr-1"></i> Terapkan Foto
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    function openEditModal(btn) {
        document.getElementById('edit_nama').value = btn.getAttribute('data-nama');
        document.getElementById('edit_jabatan').value = btn.getAttribute('data-jabatan');
        document.getElementById('editForm').action = "{{ url('admin/tentang-kami/tim') }}/" + btn.getAttribute('data-id');

        const editInput = document.getElementById('input-foto-edit-tim');
        const editWrap = document.getElementById('preview-wrapper-edit-tim');
        const editBtn = document.getElementById('btn-recrop-edit-tim');
        if (editInput) editInput.value = '';
        if (editWrap) editWrap.classList.add('hidden');
        if (editBtn) editBtn.classList.add('hidden');

        openModal('editModal');
    }

    // Buka kembali form tambah tim jika ada error validasi saat Tambah
    @if ($errors->any() && !old('_method') && old('nama'))
        openModal('tambahModal');
    @endif
</script>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
    let activeCropper = null;
    let activeFileInput = null;
    let originalFilesStore = {}; // Simpan file asli per input ID agar bisa "Crop Ulang"

    document.querySelectorAll('.js-crop-input').forEach(function (input) {
        input.addEventListener('change', function (e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                alert('Harap pilih file gambar yang valid (JPG, PNG, GIF, BMP).');
                input.value = '';
                return;
            }

            originalFilesStore[input.id] = file;
            openCropModalForInput(input, file);
        });
    });

    function recropInput(inputId) {
        const input = document.getElementById(inputId);
        const file = originalFilesStore[inputId] || (input && input.files && input.files[0]);
        if (!input || !file) return;
        openCropModalForInput(input, file);
    }

    function openCropModalForInput(input, file) {
        activeFileInput = input;
        const modal = document.getElementById('cropImageModal');
        const img = document.getElementById('cropperTargetImage');
        const titleEl = document.getElementById('cropModalTitle');

        titleEl.innerHTML = '<i class="fas fa-crop-alt"></i> ' + (input.dataset.title || 'Sesuaikan / Crop Foto');

        if (activeCropper) {
            activeCropper.destroy();
            activeCropper = null;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            img.src = event.target.result;
            modal.classList.remove('hidden');

            const aspectAttr = parseFloat(input.dataset.aspect);
            const initialAspect = isNaN(aspectAttr) ? NaN : aspectAttr;

            activeCropper = new Cropper(img, {
                aspectRatio: initialAspect,
                viewMode: 1,
                autoCropArea: 0.9,
                responsive: true,
                background: false,
            });
        };
        reader.readAsDataURL(file);
    }

    function setCropAspect(ratio) {
        if (activeCropper) {
            activeCropper.setAspectRatio(ratio);
        }
    }

    function cropperAction(action, value) {
        if (!activeCropper) return;
        if (action === 'zoom') activeCropper.zoom(value);
        if (action === 'rotate') activeCropper.rotate(value);
        if (action === 'reset') activeCropper.reset();
    }

    function cancelCropModal() {
        const modal = document.getElementById('cropImageModal');
        modal.classList.add('hidden');
        if (activeCropper) {
            activeCropper.destroy();
            activeCropper = null;
        }
        activeFileInput = null;
    }

    function applyCropImage() {
        if (!activeCropper || !activeFileInput) return;

        const canvas = activeCropper.getCroppedCanvas({
            maxWidth: 1200,
            maxHeight: 1200,
            fillColor: '#ffffff',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        if (!canvas) return;

        const input = activeFileInput;
        const originalName = (originalFilesStore[input.id]?.name || 'cropped-image').replace(/\.[^/.]+$/, '');

        canvas.toBlob(function (blob) {
            if (!blob) return;

            const croppedFile = new File([blob], originalName + '-cropped.jpg', {
                type: 'image/jpeg',
                lastModified: Date.now(),
            });

            // Masukkan file hasil crop ke input file agar terkirim saat form disubmit
            const dt = new DataTransfer();
            dt.items.add(croppedFile);
            input.files = dt.files;

            // Update gambar preview di form
            const previewEl = document.getElementById(input.dataset.previewId);
            const wrapperEl = document.getElementById(input.dataset.wrapperId);
            const recropBtn = document.getElementById(input.dataset.recropId);

            if (previewEl) {
                previewEl.src = canvas.toDataURL('image/jpeg', 0.9);
            }
            if (wrapperEl) {
                wrapperEl.classList.remove('hidden');
            }
            if (recropBtn) {
                recropBtn.classList.remove('hidden');
            }

            cancelCropModal();
        }, 'image/jpeg', 0.9);
    }
</script>
@endpush
