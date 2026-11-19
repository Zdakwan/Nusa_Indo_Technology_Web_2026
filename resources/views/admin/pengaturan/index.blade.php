@extends('layouts.admin')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Pengaturan Akun</h1>
    <p class="text-sm text-gray-500 mt-1">Kelola informasi profil dan keamanan akun Admin Anda.</p>
</div>

@if(session('success'))
    <div class="mb-6 px-4 py-3 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center justify-between" role="alert">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button type="button" class="text-green-700 hover:text-green-900" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 px-4 py-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
        <div class="flex items-center gap-2 mb-2">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="font-bold">Terjadi Kesalahan:</span>
        </div>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- FORM PENGATURAN AKUN -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-3xl mb-6">
    <div class="p-6 md:p-8">
        <form action="{{ route('admin.pengaturan.update') }}" method="POST">
            @csrf
            @method('PUT')

            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Informasi Profil</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#254261] focus:border-[#254261] block w-full p-2.5" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#254261] focus:border-[#254261] block w-full p-2.5" placeholder="Contoh: 081234567890">
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#254261] focus:border-[#254261] block w-full p-2.5" required>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2 mt-8">Keamanan Akun</h3>
            <p class="text-sm text-gray-500 mb-4">Kosongkan kolom password jika Anda tidak ingin mengubahnya.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Password Baru dengan Ikon Mata -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Password Baru</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#254261] focus:border-[#254261] block w-full p-2.5 pr-10" placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password', 'icon-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i id="icon-password" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password dengan Ikon Mata -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#254261] focus:border-[#254261] block w-full p-2.5 pr-10" placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password_confirmation', 'icon-confirm')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i id="icon-confirm" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 mt-4">
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-[#254261] hover:bg-[#1a2f45] rounded-lg shadow-sm transition-colors focus:ring-4 focus:outline-none focus:ring-blue-300">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- KARTU INTEGRASI APLIKASI MOBILE (QR CODE) -->
{{-- Container utama berupa Card putih dengan bayangan (shadow) --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-3xl mb-10">

    {{-- flex-col untuk HP (atas-bawah), md:flex-row untuk PC (kiri-kanan) --}}
    <div class="p-6 md:p-8 flex flex-col md:flex-row items-center gap-6">

        {{-- shrink-0: Mencegah area QR Code mengecil terhimpit teks --}}
        <div class="shrink-0 p-4 bg-white border border-gray-200 rounded-xl shadow-sm flex flex-col items-center justify-center">

            {{--
               1. {!! !!} (Unescaped): Wajib agar dirender jadi visual kotak QR, bukan teks HTML mentah.
               2. DNS2D & 'QRCODE': Class khusus untuk generate QR 2 Dimensi.
               3. 5, 5: Ukuran pixel agar polanya presisi bujur sangkar.
            --}}
            {!! \DNS2D::getBarcodeHTML('https://drive.google.com/file/d/1zzqftXPkR4ZUDi1gm_oZ_GAbN-YA8Nle/view?usp=sharing', 'QRCODE', 5, 5) !!}

            <span class="text-xs font-bold text-gray-500 mt-3 tracking-widest uppercase">Scan Me</span>
        </div>

        {{-- Area Teks & Tombol --}}
        <div class="text-center md:text-left">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Aplikasi Mobile Nusa Indo Tech</h3>
            <p class="text-sm text-gray-600 mb-4">
                Pindai QR Code di samping menggunakan kamera <i>smartphone</i> atau aplikasi scanner Anda untuk mengunduh dan menginstal aplikasi Android (.apk) Nusa Indo Tech secara langsung.
            </p>

            {{-- target="_blank": Membuka link Google Drive di tab baru --}}
            <a href="https://drive.google.com/file/d/1zzqftXPkR4ZUDi1gm_oZ_GAbN-YA8Nle/view?usp=sharing" target="_blank" class="inline-flex items-center px-4 py-2 text-sm font-medium text-[#254261] bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                <i class="fas fa-external-link-alt mr-2"></i> Kunjungi Tautan Unduhan
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
