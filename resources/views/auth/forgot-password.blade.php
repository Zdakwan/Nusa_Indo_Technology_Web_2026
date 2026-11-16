@extends('layouts.auth')

@section('title', 'Lupa Password - Nusa Indo Tech')

@section('left_title')
    Pulihkan<br>Akun Anda
@endsection

@section('left_desc', 'Masukkan email yang terdaftar. Kami akan mengirimkan link untuk membuat password baru.')

@section('left_extra')
    <div class="flex items-start">
        <div class="flex-shrink-0 mt-0.5">
            <i class="fas fa-envelope text-[#f7bd55] text-xl"></i>
        </div>
        <div class="ml-4">
            <h3 class="text-sm font-bold text-gray-800">Cek Email Anda</h3>
            <p class="text-xs text-gray-500 mt-1 leading-relaxed">Link reset dikirim ke email akun Anda. Periksa juga folder spam bila belum masuk.</p>
        </div>
    </div>
    <div class="flex items-start">
        <div class="flex-shrink-0 mt-0.5">
            <i class="fas fa-clock text-[#f7bd55] text-xl"></i>
        </div>
        <div class="ml-4">
            <h3 class="text-sm font-bold text-gray-800">Berlaku 60 Menit</h3>
            <p class="text-xs text-gray-500 mt-1 leading-relaxed">Demi keamanan, link hanya berlaku 60 menit dan sekali pakai.</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-md w-full mx-auto">
        <h2 class="text-4xl font-extrabold text-white mb-3">Lupa Password</h2>
        <p class="text-sm text-gray-300 mb-8">Masukkan email akun Anda untuk menerima link reset password.</p>

        @if(session('error'))
            <div class="bg-red-100 border border-red-300 rounded-lg p-3 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-times-circle text-red-600 mt-1 mr-2"></i>
                    <div>
                        <h3 class="text-red-800 font-bold text-sm">Gagal!</h3>
                        <p class="text-red-700 text-xs mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 rounded-lg p-3 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 mt-1 mr-2"></i>
                    <div>
                        <h3 class="text-green-800 font-bold text-sm">Berhasil!</h3>
                        <p class="text-green-700 text-xs mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-300 rounded-lg p-3 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-times-circle text-red-600 mt-1 mr-2"></i>
                    <p class="text-red-700 text-xs mt-1">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" autocomplete="off">
            @csrf

            <div class="input-box rounded-2xl px-5 py-3 mb-6 shadow-sm">
                <label class="block text-[11px] font-bold text-gray-400 tracking-wide uppercase mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocapitalize="none"
                    spellcheck="false"
                    class="w-full bg-transparent border-none outline-none text-white text-sm font-medium focus:ring-0 p-0"
                    placeholder="Masukkan email terdaftar Anda">
            </div>

            <button type="submit" class="w-full btn-yellow hover:bg-yellow-500 font-extrabold py-4 rounded-full transition duration-300 text-sm shadow-lg">
                Kirim Link Reset
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-gray-300 font-medium">
            Ingat password Anda?
            <a href="{{ route('login') }}" class="font-bold text-[#f7bd55] hover:underline">Kembali ke Login</a>
        </div>
    </div>
@endsection